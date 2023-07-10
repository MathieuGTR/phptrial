<?php

// YOUR FUNCTIONS CAN LIVE HERE 🏠

function checkSize ($data){
    if ($data > 2097152){
        echo "<p>File too big</p>";
        return false;
    }
    else return true;
}