<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
    <title>My Goals App</title>
</head>
    
<body>
    <header>
        <div id="h_logo">My Goals App</div>
        <div id="h_stats">
            <div>Points score today: <span id="dailyScore">0</span></div>
            <span class="separator y-ax"></span>
            <?php if ($goalProgress || $goalProgress === "0") { ?>
            <div>Daily goals progress: <?= $goalProgress ?>%</div>
            <?php } else { ?>
            <div>Daily goals progress: --</div>
            <?php } ?>
        </div>
    </header>
    <main>
