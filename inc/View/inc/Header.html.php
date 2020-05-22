<!DOCTYPE html>
<html lang="en">
    
    <head>
        <title>Altero Test - <?= $page_name ?></title>
        
        <?php foreach( $this->css as $css ): ?>
            <link type="text/css" rel="stylesheet" href="/public/css/<?= $css ?>"/>
        
        <?php endforeach ?>
    </head>
    <body>

        <div class="header">
            Altero Test - <?= $page_name ?>
        </div>
        