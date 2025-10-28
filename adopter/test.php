<?php
session_start();
var_dump($_SESSION);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Test Display</title>
  </head>
  <body style="background:#f3f3f3; font-family:sans-serif;">
    <h2 style="color:red; font-size:24px;">TEST TEXT — SHOULD BE VISIBLE</h2>

    <section class="summary-test" style="display:grid; grid-template-columns:1fr 1fr; gap:20px; background:#eef; padding:20px;">
      <div style="background:white; padding:20px;">Box A</div>
      <div style="background:white; padding:20px;">Box B</div>
      <div style="background:white; padding:20px;">Box C</div>
      <div style="background:white; padding:20px;">Box D</div>
    </section>
  </body>
</html>
