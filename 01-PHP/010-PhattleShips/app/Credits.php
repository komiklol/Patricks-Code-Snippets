<?php
  function credits($messageContents) {
    $type = "Credits PH EF FF";
    $shipRotation = "null";

    $playfieldStyle1 = 'style="width: 100%; height: 100%; display: grid; grid-template-columns: repeat(';
    $playfieldStyle2 = ', 1fr); grid-template-rows: repeat(';
    $playfieldStyle3 = ', 1fr); gap: 0;">';

    /////////////////////////////////////////////////////////////////////////////////////////////
    //                              Enemy Field  / Title Screen                                //
    /////////////////////////////////////////////////////////////////////////////////////////////
    $sizeEnemyField = [25, 14];
    // TitleScreen
    $enemyField = '<div class="Credits" ' . $playfieldStyle1 . $sizeEnemyField[0] . $playfieldStyle2 . $sizeEnemyField[1] . $playfieldStyle3;
    $enemyField .= '<div style="grid-column: 4 / 24; grid-row: 2 / 7;">';
    $enemyField .= '<img src="../img/Assets/PhattleShips-by_komiklool.png" alt="TitleScreen" style="width: 100%; height: 100%; object-fit: scale-down; object-position: center;">';
    $enemyField .= '</div>';
    /////////////////////////////////////////////////////////////////////////////////////////////
    //                           Friendly Field  / Silly Screen                                //
    /////////////////////////////////////////////////////////////////////////////////////////////
    $sizeFriendlyField = [25, 14];
    $friendlyField = '<div class="Credits Bottom" ' . $playfieldStyle1 . $sizeFriendlyField[0] . $playfieldStyle2 . $sizeFriendlyField[1] . $playfieldStyle3;

    $gimp = '<img src="../img/Assets/GIMP_icon.png" alt="Gimp Icon">';
    $gimp .= "<p>Gimp, used for the Ships and for editing the Texts.</br> Did I mention that it's free ?</br> It is Free :)</br> Now i sayed it.</p>";
    $gimp .= '<a href="https://www.gimp.org" target="_blank" title="Homepage of Gimp">Gimp.org</a>';
    $friendlyField .= '<div class="BoxElement CreditsElement" style="grid-row: 2 / 14 ; grid-column: 2 / 7 ;">' . $gimp . '</div>';

    $fontMeme = '<h1 style="color: #C80707; font-family: Impact,serif; font-size: xxx-large;">Font Meme</h1>';
    $fontMeme .= '<p>I used Font Meme -> Pixel Fonts, fot the Bigger texts. </br>I used KleinHeadline if someone was wondering :)</p>';
    $fontMeme .= '<a href="https://fontmeme.com/pixel-fonts/" target="_blank" title="Go to Pixel Fonts">Pixel Fonts</a>';
    $friendlyField .= '<div class="BoxElement CreditsElement" style="grid-row: 2 / 14; grid-column: 8 / 13;">' . $fontMeme . '</div>';

    $markSimpson = '<img src="../img/Assets/PrinzEugn-MarkSimpson.png" alt="FighterJet" style="rotate: 90deg">';
    $markSimpson .= "<p>Some Sprites im using are made by Prinz-Eugn/Mark Simpson.</br>Copyright 2001-2008(c)</br> Check him out Below!</p>";
    $markSimpson .= '<a href="http://prinzeugn.deviantart.com/" target="_blank" title="DevianArt">Print-Eugn on DevianArt</a>';
    $friendlyField .= '<div class="BoxElement CreditsElement" style="grid-row: 2 / 14 ; grid-column: 14 / 19 ;">' . $markSimpson . '</div>';

    $friendlyField .= '</div>';

    /////////////////////////////////////////////////////////////////////////////////////////////
    //                                PlayerHUD  / Menu                                        //
    /////////////////////////////////////////////////////////////////////////////////////////////
    $playerHUD = makePlayerHUD("Credits");

    //Send Out///////////////////////////////////////////////////////////////////////////////////
    $messageContents->client->send(readyForSend($type, $playerHUD, $enemyField, $friendlyField, $sizeEnemyField, $sizeFriendlyField, 0, $shipRotation));
  }





