cwh_hobo
========

cwh_hobo is a [Textpattern](http://textpattern.com) plugin that will display a random hobo name from John Hodgeman's list of 700 hobo names taken from his book [The Areas of my Expertise](https://www.amazon.ca/Areas-Expertise-Instructive-Annotation-2006-09-05/dp/B0163DW6CW/) (more information [here](https://en.wikipedia.org/wiki/The_Areas_of_My_Expertise)). Once installed, you use it by simply placing the follwing in any article, page, or form within Textpattern:

```php
<txp:cwh_hobo />
```

This will produce a random hobo name, and number, like so:

```php
#171: Twink the Reading-Room Snoozer
```

cwh_hobo also accepts two attributes: `wraptag` and `class`. So, for instance, if you want to wrap the output in a paragraph tag with the class of "hobo", you'd have the following:

```php
<txp:cwh_hobo wraptag="p" class="hobo" />
```

which would produce:

```php
<p class="hobo">#171: Twink the Reading-Room Snoozer</p>
```
