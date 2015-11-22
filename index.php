<?php
include_once($_SERVER['DOCUMENT_ROOT']."/config.php");
//header
include("./template_pagine/header.php");
?>

<div class="contenitore">
    <div  class="contenuto">
        <!-- contenuto -->
        <div class="newscontainer">
            <?php
            $newsMGR->stampaNews();
            ?>
        </div>
    </div>
</div>
<?php
//footer
include("./template_pagine/footer.php");
?>