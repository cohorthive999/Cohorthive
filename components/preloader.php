<!-- preloader.php -->
<style>
  #preloader {
    background: white url("images/preloader.gif") no-repeat center center;
    background-size:contain;
    background-repeat:no-repeat;
    width: 100%;
    height: 100%;
    position: fixed;
    z-index: 100000;
  }
</style>

<div id="preloader"></div>

<script>
  var loader = document.getElementById('preloader');
  window.addEventListener("load", function () {
    loader.style.display = "none";
  })
</script>

