<?php require dirname(__FILE__). '/__settings__.php'; app(); ?>
<app name="Bibitter" ns="bibitter">
    <handler>
        <map url="" class="Bibitter" method="current" template="index.html" />
        <map url="history$" class="Bibitter" method="history" template="history.html" />
        <map url="about$" template="about.html" />
    </handler>
</app>