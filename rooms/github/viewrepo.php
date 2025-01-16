<?php
session_start();
require 'components/config.php';

// Ensure GitHub token is in session
if (!isset($_SESSION['github_token'])) {
    header('Location: index.php');
    exit;
}

$token = $_SESSION['github_token'];

// Ensure 'owner' and 'repo' are in query params
if (isset($_GET['owner']) && isset($_GET['repo'])) {
    $owner = urlencode($_GET['owner']);
    $repo = urlencode($_GET['repo']);
    $apiBase = "https://api.github.com/repos/$owner/$repo";
} else {
    header('Location: index.php');
    exit;
}

// Function to make GitHub API requests
function github_api_request($url, $token, $method = 'GET', $data = null)
{
    $ch = curl_init($url);
    $headers = [
        "Authorization: token $token",
        "User-Agent: PHP Script",
        "Accept: application/vnd.github.v3+json"
    ];
    if ($method === 'PUT' && $data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $headers[] = "Content-Type: application/json";
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        die("Curl error: $error");
    }
    curl_close($ch);

    if ($http_code !== 200 && $http_code !== 201) {
        die("Error fetching data from GitHub API: HTTP $http_code. Response: $response");
    }
    return json_decode($response, true);
}

// Function to list repository contents
function list_repo_contents($url, $token)
{
    return github_api_request($url, $token);
}

// Function to get repository metadata
function get_repo_metadata($apiBase, $token)
{
    $urls = [
        'contributors' => "$apiBase/contributors",
        'branches' => "$apiBase/branches",
        'commits' => "$apiBase/commits",
        'pulls' => "$apiBase/pulls",
        'releases' => "$apiBase/releases"
    ];

    $metadata = [];
    foreach ($urls as $key => $url) {
        $metadata[$key] = github_api_request($url, $token);
    }
    return $metadata;
}

// Function to update file content on GitHub
function update_file_content($url, $token, $content, $sha, $message)
{
    $data = [
        'message' => $message,
        'content' => base64_encode($content),
        'sha' => $sha
    ];
    return github_api_request($url, $token, 'PUT', $data);
}

// Get repository metadata
$metadata = get_repo_metadata($apiBase, $token);

// If path is set, get contents of that path
if (isset($_GET['path'])) {
    $path = urlencode($_GET['path']);
    $url = "$apiBase/contents/$path";
    $contents = list_repo_contents($url, $token);
    echo json_encode($contents);
    exit;
}

// If updating file content
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['path'], $_POST['content'], $_POST['sha'])) {
    $path = $_POST['path'];
    $content = $_POST['content'];
    $sha = $_POST['sha'];
    $message = "Updating file $path via Cohort Hive X GitHub";

    $url = "$apiBase/contents/$path";
    $response = update_file_content($url, $token, $content, $sha, $message);
    echo json_encode($response);
    exit;
}

// Get root contents of the repository
$rootContents = list_repo_contents("$apiBase/contents", $token);

?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title><?php echo isset($_GET['repo']) ? $_GET['repo'] : 'GitHub API Viewer'; ?></title>
    <link rel="icon" href="../../images/favicon.png" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/git.css">
</head>

<body>
    <div class="navbar navbar-expand-lg navbar-dark bg-dark top-panel">
        <div class="d-flex align-items-center">
            <div class="backBtn"><a href="index.php" class="backButton"><i class="fa-solid fa-chevron-left"></i></a></div>
            <div class="pageTitle ml-5">Cohort Hive X GitHub</div>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <button class="btn btn-primary m-2" onclick="showCommitHistory()">Commit History</button>
                </li>
                <li class="nav-item">
                    <button class="btn btn-primary m-2" onclick="showContributors()">Contributors</button>
                </li>
                <li class="nav-item">
                    <button class="btn btn-primary m-2" onclick="showBranches()">Branches</button>
                </li>
                <li class="nav-item">
                    <button class="btn btn-primary m-2" onclick="showPullRequests()">Pull Requests</button>
                </li>
            </ul>
        </div>
    </div>
    <div class="container">
        <div class="left-panel" id="file-navigator">
            <!-- Folder and File Structure will be loaded here -->
        </div>
        <div class="center-panel" id="file-content">
            <!-- File Content will be displayed here -->
        </div>
    </div>
    <script>
    const repo = '<?php echo $_GET['repo']; ?>';
    const owner = '<?php echo $_GET['owner']; ?>';

    async function loadFileNavigator(path = '') {
        const response = await fetch(`?owner=${owner}&repo=${repo}&path=${encodeURIComponent(path)}`);
        if (!response.ok) {
            alert('Error fetching repository contents: ' + response.status);
            return;
        }
        const data = await response.json();
        displayFileNavigator(data, path);
    }

    function displayFileNavigator(data, path) {
        const navigator = document.getElementById('file-navigator');
        if (path === '') {
            navigator.innerHTML = buildFileTree(data);
        } else {
            const currentFolder = document.querySelector(`[data-path="${path}"]`);
            const folderContent = buildFileTree(data);
            currentFolder.innerHTML += folderContent;
        }
    }

    function buildFileTree(data) {
        let html = '<ul>';
        data.forEach(item => {
            if (item.type === 'dir') {
                html += `<li class="folder-toggle custom-folder" data-path="${item.path}" onclick="toggleFolder(event, '${item.path}')">${item.name}</li>`;
            } else {
                html += `<li><button class="file-button custom-file" onclick="loadFileContent(event, '${item.path}')">${item.name}</button></li>`;
            }
        });
        html += '</ul>';
        return html;
    }

    function toggleFolder(event, path) {
        event.stopPropagation();
        const folderElement = event.currentTarget;
        if (folderElement.classList.contains('open')) {
            folderElement.classList.remove('open');
            const subFolders = folderElement.querySelector('ul');
            if (subFolders) {
                subFolders.style.display = 'none';
            }
        } else {
            folderElement.classList.add('open');
            const subFolders = folderElement.querySelector('ul');
            if (subFolders) {
                subFolders.style.display = 'block';
            } else {
                loadFileNavigator(path);
            }
        }
    }

    async function loadFileContent(event, filePath) {
        event.stopPropagation();
        const response = await fetch(`?owner=${owner}&repo=${repo}&path=${encodeURIComponent(filePath)}`);
        if (!response.ok) {
            alert('Error fetching file content: ' + response.status);
            return;
        }
        const data = await response.json();

        const contentPanel = document.getElementById('file-content');
        const fileType = data.name.split('.').pop().toLowerCase();

        contentPanel.innerHTML = `
            <div>
                <button class="btn btn-secondary" id="editButton" style="display: ${['jpg', 'jpeg', 'png', 'gif', 'bmp'].includes(fileType) ? 'none' : 'inline-block'};" onclick="enableEditing('${data.path}', '${data.sha}', '${data.content}')">Edit</button>
                <button class="btn btn-success" id="saveButton" style="display: none;" onclick="saveFileContent('${data.path}', '${data.sha}')">Save</button>
            </div>`;

        if (data.encoding === 'base64') {
            if (['jpg', 'jpeg', 'png', 'gif', 'bmp'].includes(fileType)) {
                contentPanel.innerHTML += `<img src="data:image/${fileType};base64,${data.content}" alt="${data.name}" style="max-width: 100%; max-height: 100%;">`;
            } else {
                contentPanel.innerHTML += '<pre id="file-content-area" class="file-content">' + atob(data.content).replace(/[<>&]/g, c => ({ '<': '&lt;', '>': '&gt;', '&': '&amp;' }[c])) + '</pre>';
            }
        } else {
            contentPanel.innerHTML += '<pre id="file-content-area" class="file-content">' + atob(data.content).replace(/[<>&]/g, c => ({ '<': '&lt;', '>': '&gt;', '&': '&amp;' }[c])) + '</pre>';
        }
    }

    function enableEditing(path, sha, content) {
        console.log('Editing enabled for:', path); // Debug statement
        const contentArea = document.getElementById('file-content-area');
        contentArea.contentEditable = 'true';
        contentArea.style.border = '1px solid #ccc'; // Visual cue for editable area
        contentArea.focus();
        document.getElementById('editButton').style.display = 'none';
        document.getElementById('saveButton').style.display = 'inline-block';
    }

    async function saveFileContent(path, sha) {
        const content = document.getElementById('file-content-area').innerText;
        const response = await fetch('', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                path: path,
                content: btoa(content),
                sha: sha
            })
        });

        if (response.ok) {
            alert('File saved successfully!');
            document.getElementById('file-content-area').contentEditable = 'false';
            document.getElementById('editButton').style.display = 'inline-block';
            document.getElementById('saveButton').style.display = 'none';
        } else {
            alert('Error saving file: ' + response.status);
        }
    }

    function showCommitHistory() {
        const commits = <?php echo json_encode($metadata['commits']); ?>;
        const contentPanel = document.getElementById('file-content');
        contentPanel.innerHTML = '<h2>Commit History</h2>';
        contentPanel.innerHTML += '<ul>' + commits.map(commit => `<li>${commit.commit.message} by ${commit.commit.author.name}</li>`).join('') + '</ul>';
    }

    function showContributors() {
        const contributors = <?php echo json_encode($metadata['contributors']); ?>;
        const contentPanel = document.getElementById('file-content');
        contentPanel.innerHTML = '<h2>Contributors</h2>';
        contentPanel.innerHTML += '<ul>' + contributors.map(contributor => `<li>${contributor.login}</li>`).join('') + '</ul>';
    }

    function showBranches() {
        const branches = <?php echo json_encode($metadata['branches']); ?>;
        const contentPanel = document.getElementById('file-content');
        contentPanel.innerHTML = '<h2>Branches</h2>';
        contentPanel.innerHTML += '<ul>' + branches.map(branch => `<li>${branch.name}</li>`).join('') + '</ul>';
    }

    function showPullRequests() {
        const pulls = <?php echo json_encode($metadata['pulls']); ?>;
        const contentPanel = document.getElementById('file-content');
        contentPanel.innerHTML = '<h2>Pull Requests</h2>';
        contentPanel.innerHTML += '<ul>' + pulls.map(pr => `<li>${pr.title} by ${pr.user.login}</li>`).join('') + '</ul>';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const navbarToggle = document.querySelector('.navbar-toggler');
        const navbarNav = document.querySelector('#navbarNav');

        navbarToggle.addEventListener('click', () => {
            navbarNav.classList.toggle('show');
        });

        loadFileNavigator();
    });
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>
