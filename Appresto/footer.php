<?php
function render_footer($brand = 'RestoWEb', $place = 'Le Palais Des Saveurs', $team = 'BTS SIO') {
    $year = date('Y');
    $b = htmlspecialchars($brand, ENT_QUOTES, 'UTF-8');
    $p = htmlspecialchars($place, ENT_QUOTES, 'UTF-8');
    $t = htmlspecialchars($team, ENT_QUOTES, 'UTF-8');

    echo '<footer class="footer">';
    echo '<p>';
    echo $b . ' - &copy; ' . $year . ' ' . $p . ' - Tous droits réservés - ';
    echo '<span style="color: orange;">' . $t . '</span>';
    echo '</p>';
    echo '</footer>';
}

render_footer();
?>