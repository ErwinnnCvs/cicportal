<?php
if (extension_loaded('gd')) {
    echo "GD extension is loaded";
    print_r(gd_info());
} else {
    echo "GD extension is NOT loaded";
}
?>