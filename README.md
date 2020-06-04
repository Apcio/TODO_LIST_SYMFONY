<h2>Opis zadania</h2>
Za pomocą komponentu Symfony/Console napisać prostą aplikację CLI, która będzie pozwalała na tworzenie listy rzeczy do zrobienia.
Interfejs powinien pozwalać na:
<ol>
    <li>Dodawanie elementów do listy</li>
    <li>Usuwanie elementów z listy</li>
    <li>Możliwość oznaczania zadań jako wykonane</li>
    <li>Wylistowania wszystkich zadań posortowanych według nazwy lub daty</li>
    <li>Wylistowania zadań na dziś</li>
</ol>

<h2>Instalacja</h2>
Aplikacja napisana we frameworku Symfony 5 - wymagania techniczne systemu powinny być spełnione do uruchomienia tego frameworka.
Wszystkie zależności można zainstalować za pomocą menadżera composer:
<pre>composer install</pre>

<h2>Uruchomienie</h2>
Wylistowania wszystkich komend:
<pre>php cli/todo.php list</pre>