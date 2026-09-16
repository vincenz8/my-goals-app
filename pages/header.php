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
        <form action="." method="post">
            <span id="h_logo">My Goals App</span>
            <div class='column'>
                <input type="submit" class="box cursor-pointer" name="action" value="Manage Tasks">
                <input type="submit" class="box cursor-pointer" name="action" value="Progress Tracker">          
            </div>
            <div class='column right-align'>
                <div class='box score-display'>Points score today: <span id="dailyScore">0</span></div>
                <?php if ($goalProgress || $goalProgress === "0") { ?>
                <div class='box score-display'>Daily goals progress: <?= $goalProgress ?>%</div>
                <?php } else { ?>
                <div class='box score-display'>Daily goals progress: --</div>
                <?php } ?>
            </div>
        </form>
        <span class="separator x-ax"><hr></span>
    </header>
    <main>
