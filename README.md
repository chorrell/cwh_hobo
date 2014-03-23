cwh_hobo
========

cwh_hobo is a Textpattern plugin that will display a random hobo name from John Hodgeman's list of 700 hobo names taken from his book [The Areas of my Expertise](http://www.amazon.ca/exec/obidos/ASIN/1594482225/suburbanplayb-20) (more information [here](http://www.areasofmyexpertise.com/)). Once installed, you use it by simply placing the follwing in any article, page, or form within Textpattern:

```
<txp:cwh_hobo />
```

This will produce a random hobo name, and number, like so:

```
#171: Twink the Reading-Room Snoozer
```

cwh_hobo also accepts two attributes: @wraptag@ and @class@. So, for instance, if you want to wrap the output in a paragraph tag with the class of "hobo", you'd have the following:

```
<txp:cwh_hobo wraptag="p" class="hobo" />
```

which would produce:

```
<p class="hobo">#171: Twink the Reading-Room Snoozer</p>
```