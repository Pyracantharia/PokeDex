<?php
namespace Pok;

class Utils
{
  public static $siteName = "PhPDex";
  public static function navbar($server, string $scriptName, string $title): string
  {
    $class = '';
    if ($server === $scriptName) {
      $class = ' active';
    }

    return "<li class='nav-item'>
      <a class='nav-link $class' aria-current='page' href='$scriptName'>$title</a>
    </li>";
  }
}
