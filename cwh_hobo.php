<?php

$plugin['name'] = 'cwh_hobo';
$plugin['version'] = '0.2.1';
$plugin['author'] = 'Christopher Horrell';
$plugin['author_uri'] = 'http://horrell.ca/';
$plugin['description'] = 'Generates a random hobo name from John Hogeman\'s book "The Areas of my Expertise".';

// Plugin types:
// 0 = regular plugin; loaded on the public web side only
// 1 = admin plugin; loaded on both the public and admin side
// 2 = library; loaded only when include_plugin() or require_plugin() is called
$plugin['type'] = 0; 

@include_once('zem_tpl.php');

if (0) {
?>
# --- BEGIN PLUGIN HELP ---
h1. cwh_hobo

This plugin will display a random hobo name from John Hodgeman's list of 700 hobo names taken from his book "The Areas of my Expertise":http://www.amazon.com/Areas-My-Expertise-John-Hodgman/dp/1594482225/ref=pd_bbs_sr_1/002-8070310-4622414?ie=UTF8&s=books&qid=1172890799&sr=8-1.

To use it, simply insert the following tag wherever you want a random hobo name to appear:

bc. <txp:cwh_hobo />

and this will produce a hobo name and designated number, like so:

bc. #171: Twink the Reading-Room Snoozer

cwh_hobo also accepts two attributes: @wraptag@ and @class@. So, for instance, if you want to wrap the output in a paragraph tag with the class of "hobo", you would have the following:

bc. <txp:cwh_hobo wraptag="p" class="hobo" />

which would produce:

bc. <p class="hobo">#171: Twink the Reading-Room Snoozer</p>


# --- END PLUGIN HELP ---
<?php
}

# --- BEGIN PLUGIN CODE ---

// Attributes

function cwh_hobo($atts)
{
	extract(lAtts(array(
		'class'		 => '',
		'wraptag'	 => '',
	), $atts));
	
	// Delightfull array of hobo names
	$hobos = array(
			"#1: Stewbuilder Dennis",
			"#2: Cholly the Yegg",
			"#3: Holden the Expert Dreamtwister",
			"#4: The Rza",
			"#5: Jack Skunk",
			"#6: Jack Skunk Fils",
			"#7: Lord Dan X. Still-Standing",
			"#8: Marlon Fitz-fancy",
			"#9: Bazino Bazino, The Kid Whose Hair Is On Fire",
			"#10: Whispering-Lies McGruder",
			"#11: Nit Louse",
			"#12: Dan&#8217;l Dinsmore Tackadoo",
			"#13: Hobo Zero",
			"#14: The Silver Jacket Man",
			"#15: No-Shoulders Smalltooth Jones",
			"#16: Sistery Brothery Nabob",
			"#17: Name Withheld",
			"#18: Staniel the Spaniel",
			"#19: Frederick Bannister, the Tree Surgeon",
			"#20: Tarnose Cohen",
			"#21: Mr. Wilson Fancypants",
			"#22: Floyd Dangle",
			"#23: Shane Stoopback",
			"#24: Wicked Paul Fourteen-Toes",
			"#25: Normal-Faced Olaf",
			"#26: Tearbaby Hannity Stoop",
			"#27: The Damned Swede",
			"#28: Pierre Tin-Hat",
			"#29: Ol&#8217; Barb Stab-You-Quick",
			"#30: Mr. Whist",
			"#31: James Fenimore Cooper",
			"#32: Twistback John, the Scoliosis Sufferer",
			"#33: Sweet Daddy Champagne",
			"#34: Senator Cletus Scoffpossum",
			"#35: Horus, the Bird-Headed Fool",
			"#36: 50-Tooth Slim",
			"#37: Monk, the Monkey Man (which is to say: &#8220;the Man&#8221;)",
			"#38: Thad the Bunter",
			"#39: Balloonpopper Chillingsworth",
			"#40: All-but-Dissertation Tucker Dummychuck",
			"#41: Finnish Jim",
			"#42: Flemish Jim",
			"#43: Foreign Tomas, the Strangetalker",
			"#44: Roadhouse Ogilvy and Sons",
			"#45: Jokestealer John Selden",
			"#46: Giancarlo, Master of the Metal Trapeze",
			"#47: Dr. Bill Stain-Chin, the Boxcar Medic",
			"#48: Boxcar Ted",
			"#49: Boxcar Mick",
			"#50: Boxcars [sic] Timothy Twin",
			"#51: Boxcar Jones, the Boxcar Benjamin Disraeli",
			"#52: Boxcar Aldous Huxley",
			"#53: JR Lintstockings",
			"#54: Gila Monster Jr.",
			"#55: Irontrousers the Strong",
			"#56: &#8220;X&#8221; the Anonymous Man or Woman",
			"#57: Orphaned Reynaldo, the Child with Haunting Eyes (while there were children hoboes, Reynaldo took this when he was 45; prior to this, he was known as&#8230;[See #58])",
			"#58: Reynaldo Reynaldoson, Who Will One Day Kill His Father",
			"#59: Thoughtless Harry Hsu",
			"#60: Clinical Psychiatrist Huga Rivera",
			"#61: Peter Ox-Hands",
			"#62: Ponytail Douglas Winthrop",
			"#63: Lil&#8217; Jonny Songbird, the Songbird-Eater",
			"#64: King Snake: The Eternal Mystery",
			"#65: Ghostly Nose Silvie",
			"#66: Fonzie",
			"#67: DiCapa the Hound",
			"#68: Beef-or-Chicken Bob Nubbins",
			"#69: Honest Amelia Dirt",
			"#70: Slow Motion Jones",
			"#71: Canadian Football Pete",
			"#72: Meep Meep, the Italian Tailor",
			"#73: Jonathan William Coulton, the Colchester Kid",
			"#74: Maria the Pumpkin-Patch Crooner",
			"#75: Bix Shmix",
			"#76: Vice President Garrett Hobart",
			"#77: Stun Gun Jones",
			"#78: Prostate Davey",
			"#79: Flea Stick",
			"#80: Niles Butterbal, the Frozen Turkey",
			"#81: Todd Four-Flush",
			"#82: Stick-Legs McOhio",
			"#83: The Unanswered Question of Timothy",
			"#84: Mickey the Assistant Manager",
			"#85: Guesstimate Jones",
			"#86: Goofus",
			"#87: Gallant",
			"#88: Sir Roundbelly DeDelight",
			"#89: Newton Fig",
			"#90: Chicken Nugget Will",
			"#91: Parlor Peter, the Sneak Thief",
			"#92: Ovid",
			"#93: Bathsheba Ditz",
			"#94: Alan Pockmark, Esq.",
			"#95: Lolly Hoot Holler",
			"#96: Von Skump",
			"#97: Lonnie Choke",
			"#98: Chisolm Chesthair",
			"#99: Freak Le Freak, the Freakster",
			"#100: Rex Spangler, the Bedazzler",
			"#101: Randall Mouth-Harp",
			"#102: Chrysler LeBaron",
			"#103: The Fishin&#8217; Physician",
			"#104: Persuasive Frederick",
			"#105: Celestial Stubbs",
			"#106: Teary-Eyed Fingal",
			"#107: Mairah Nix",
			"#108: Cthulhu Carl",
			"#109: Del Folksy-Beard",
			"#110: No-Banjo Burnes",
			"#111: Chainmail Giles Godfrey",
			"#112: Lois &#8220;Charles&#8221; Ladyfinger",
			"#113: Plausible Zane Scarrey",
			"#114: Huckle Smothered",
			"#115: MmmmmDandy Dundee",
			"#116: Mountain-Humper Edgar Ames",
			"#117: Spasmodic Hilary",
			"#118: Doc Aquatic",
			"#119: Molly Bewigged",
			"#120: Cincinnati O&#8217;Gurk",
			"#121: Metuchen O&#8217;Sullivan",
			"#122: Cherry Hill O&#8217;Manley",
			"#123: Cheesequake O&#8217;Lennox",
			"#124: Booper O&#8217;Montauk",
			"#125: Zaxxon Galaxian",
			"#126: Drinky Drunky Thom, the Drunk",
			"#127: Terry Gross",
			"#128: Spooky-Night Spooky Day",
			"#129: Zipgun Gloucester Gluck",
			"#130: Human Hair Frum",
			"#131: Sherlock-Holmes-Hat Carl III",
			"#132: Patrick Intergalactic",
			"#133: Ambidextrous Stang",
			"#134: Yum-Yum Sinclair Snowballeater",
			"#135: Ponzi-Scheme Jeremiah Ponzi",
			"#136: Toodles Strunk",
			"#137: Monkeybars Matthew Manx",
			"#138: Pineneedle-Jacket Jericho Fop",
			"#139: Robert the Tot",
			"#140: Robert the Child-Size",
			"#141: Robert the Minuscule",
			"#142: Robert the Wee",
			"#143: Robert Fits-in-a-Case",
			"#144: Robert Eats-for-Free",
			"#145: Robert Is-He-an-Elf? (The seven Silk brothers, all named Robert, were also known for the small stature and predictable bitterness.)",
			"#146: Dennis Big-Ear Fox",
			"#147: Jethro the Pagan",
			"#148: Asterix the Gaul",
			"#149: Black Bolt, King of the Inhumans",
			"#150: Strictly Local Henry Bobtail",
			"#151: Manny the High-Ranking Mason",
			"#152: Fry-Pan Jim Fry",
			"#153: Slo-Mo Deuteronomy",
			"#154: Half-Bearded Mark",
			"#155: Knee-Brace Kenny",
			"#156: Morris the Personal Trainer",
			"#157: Thundertwine",
			"#158: Cleats Onionpocket",
			"#159: Deformed Abe",
			"#160: Trainwhistle Abejundio",
			"#161: David No-Ears",
			"#162: Achilles Snail-Hair the Buddha",
			"#163: Frog-Eatin&#8217; Lou",
			"#164: Admiral&#8217;s Club Wilbur",
			"#165: Max Meatboots, the First-Class Lounger",
			"#166: Dora the Explorer",
			"#167: Ms. Mary Manx, the Tailless Cat",
			"#168: Free-Peanuts Doug",
			"#169: Steve the Human Tunneler",
			"#170: Redball Charlie Dickens",
			"#171: Twink the Reading-Room Snoozer",
			"#172: Microfiche Roy, the Side-Scroller",
			"#173: McGurk, Who May Be Found by the Card Catalogue",
			"#174: Booster D&#8217;Souza",
			"#175: Commodore Sixty-Four",
			"#176: Moped Enid, the Mopedist",
			"#177: Lamant the Junkman",
			"#178: Fast-Neck Nell",
			"#179: Bill Never-Uses-a-Cookbook",
			"#180: Bee-Beard",
			"#181: Lil&#8217; Max Meatboots",
			"#182: The Personal Secretary to Jed",
			"#183: Dee Snider",
			"#184: Sausage Patty",
			"#185: Desert Locust",
			"#186: Gummy Miles",
			"#187: Gyppo Moot, the Enigma Machine",
			"#188: Ol&#8217; Stiffpants",
			"#189: Skywise the Sexual Elf",
			"#190: Craine T. Eyebrow-Smeller",
			"#191: Lonely Heiney Alan Meister",
			"#192: Shakey Aitch the Boneyard Concierge",
			"#193: Woody Damn",
			"#194: Alatar",
			"#195: Pallando",
			"#196: Saltfish Bunyan",
			"#197: Poor, Poor, Poor Charlie Short",
			"#198: Venomous Byron",
			"#199: Five-Chambered-Stomach Mort St. John",
			"#200: Gravybelly Dunstan",
			"#201: Extra-Skin Dave",
			"#202: Beanbag-Chair Bill",
			"#203: Grant Sharpnails, the Scratcher",
			"#204: Tommy Lice-Comb",
			"#205: &#8220;Medicated Shampoo&#8221; Jonah Jump",
			"#206: General Woundwort, the Giant Rabbit",
			"#207: Genius L. Cravat, the Gentleman",
			"#208: Giant Bat Wings Roland",
			"#209: Nick Nolte",
			"#210: Salty Salty Friday",
			"#211: Fatman and the Creature (note: there was no creature)",
			"#212: Cecelia Graveside",
			"#213: Hoosegow Earl French",
			"#214: Stymie Stonewrist",
			"#215: Roadrunner &#8220;Meep Meep&#8221; Fabong",
			"#216: Bruised-Rib Johansson, the Beefer",
			"#217: Joachim Bat-in-Hair",
			"#218: Food-Eating Micah",
			"#219: Rubbery Dmitry, the Mad Monk",
			"#220: Honey Bunches of Donald",
			"#221: Crispy Morton",
			"#222: Feminine Forearms Rosengarten",
			"#223: Two-Headed Mike Hoover",
			"#224: Manny Stillwaggon, the Man with the Handlebar Eyebrows",
			"#225: Bean-Hoarder Newt",
			"#226: Texas Emil",
			"#227: The Moor of Venice",
			"#228: Averroes Nix",
			"#229: Human Hair Blanket Morris Burnes",
			"#230: Canadian Paul Tough",
			"#231: Crooner Sy",
			"#232: Manuel Pants-Too-High",
			"#233: Sylvia Patience Hidden-Forks",
			"#234: Sung, the Land Pirate",
			"#235: Opie, the Boston Bum",
			"#236: Hard-Flossing Hope Peak",
			"#237: Stingo the Bandana Origami Prodigy",
			"#238: Franklin Ape and His Inner Ear Infection",
			"#239: Questionable-Judgement Theodore Stomachbrace",
			"#240: Thermos H. Christ",
			"#241: Sir Mix-a-Lot",
			"#242: The Nine Doctor Whos",
			"#243: Lord Winston Two-Monocles",
			"#244: The Freewheelin&#8217; Barry Sin",
			"#245: Diego the Spark-Spitter",
			"#246: American Citizen Zane Pain",
			"#247: Abraham, the Secret Collector of Decorative China",
			"#248: Linty Sullivan, the Lint-Collector",
			"#249: Socks Monster",
			"#250: Ma Churchill",
			"#251: Pappy Churchill",
			"#252: The Young Churchill",
			"#253: The Young Churchill&#8217;s Hated Bride",
			"#254: Churchill-Lover Phineas Redfish",
			"#255: Crispus T. Muzzlewitt",
			"#256: Stain-Sucker Duncan",
			"#257: Dick the Candy Dandy",
			"#258: Albuterol Inhaler Preson McWeak",
			"#259: Longtime Listener, First-Time Caller",
			"#260: Mastiff Mama",
			"#261: Tennessee Ernie Dietz",
			"#262: Sharkey, the Secret Cop",
			"#263: Gooseberry Johnson, Head Brain of the Hobosphere",
			"#264: Weekend-Circular Deborah",
			"#265: Marcus Chickenstock",
			"#266: Stunted Newton",
			"#267: Magnus Shortwave",
			"#268: U.S. Fool",
			"#269: Manatee the Railyard Toreador",
			"#270: Utah Manfred Succor-Munt",
			"#271: Laura Delite",
			"#272: Edwin Winnipeg",
			"#273: Eyepatch Resse Andiron",
			"#274: Tom False-Lips Real-Teeth",
			"#275: Fabulon Darkness",
			"#276: Cricket-Eating Charles Digges",
			"#277: Pally McAffable, Everybody&#8217;s Friend",
			"#278: Sully Straightjacket",
			"#279: Half-Dollar Funk Nelson",
			"#280: Whitman Sampler",
			"#281: Chili-Mix Wilma Bensen",
			"#282: Sting, the Glowing Blade",
			"#283: Professor Challenger",
			"#284: Lil&#8217; Shorty Longhorn",
			"#285: Rumpshaker Phil",
			"#286: Swing State Myron",
			"#287: Alistair Crowley, the Devil",
			"#288: Gutthrower Sy Salt",
			"#289: Sweetback Barney, the Dilettante",
			"#290: The Car-Knocker Killer",
			"#291: The Chamberlain",
			"#292: The Emperor",
			"#293: The Ritual-Master",
			"#294: The Garthim-Master",
			"#295: The Scientist",
			"#296: The Gourmand",
			"#297: The Slave-Master",
			"#298: The Treasurer",
			"#299: The Scroll Keeper",
			"#300: The Ornamentalist",
			"#301: Captain Slick-Talk",
			"#302: Sackfist, the Tapdancing Trombo",
			"#303: Souvenir-Selling Mlodinow",
			"#304: Blind Buck and &#8220;Woozy,&#8221; the Invisible Seeing-Eye Dog",
			"#305: Roundhouse Farter",
			"#306: Red Ball Pnutz",
			"#307: Fake Cockney Accent Alan Strippe",
			"#308: Air and Whiskey Dale McGlue",
			"#309: Johnny RC Airplane",
			"#310: Narcotic Morgan Suds",
			"#311: Sir Francis Drank",
			"#312: Mahayana Mike",
			"#313: Miniyana Geoffrey",
			"#314: Three-Bean Otz",
			"#315: Maury the Monsoon",
			"#316: Czech Czarlie Czill",
			"#317: Sssssssssssssssss, the Hisser",
			"#318: Thanatos Koch",
			"#319: Henry Eatsmelts",
			"#320: Modem-Sniffer Gunderson",
			"#321: Half-Albino Alejandro",
			"#322: Gluttonous-Slim",
			"#323: Ragweed-Allergic Matt",
			"#324: Amorous Luminous Dirk",
			"#325: Moray Eel Ken Elmer",
			"#326: The Railbender",
			"#327: Antonio the Ombudsman",
			"#328: Karl Solenoid IV",
			"#329: Czar King Rex the Glorious Leader",
			"#330: Andy Bunkum",
			"#331: Plastic-Moustache Mortimer Tall",
			"#332: Samuel Gel Insole",
			"#333: Lemuel Gel Insole",
			"#334: Amanda Until",
			"#335: Crispy Whiskery",
			"#336: Robert Louis Stevenson, the Pirate",
			"#337: Hobo Overload",
			"#338: Leopard Print Steven Kane",
			"#339: Astonishing Shaun Eyelash",
			"#340: Billy Creak Knees",
			"#341: Owlie",
			"#342: Anwar, the Bionic",
			"#343: Reasonably Priced Motel Resse Unger",
			"#344: Fibery Dana",
			"#345: Cranberry Sauce Oppenheimer",
			"#346: Nancified Frederick",
			"#347: The Loon",
			"#348: Itinerant Jane",
			"#349: Holy Hannah Hottentot-Smythe",
			"#350: Fleabottle Boone",
			"#351: Amazin&#8217; Jack Caroo",
			"#352: Stupefying P, the Riddle-Maker",
			"#353: Todd Flaky-Palms",
			"#354: Waspwaist Fritz",
			"#355: Judge Roughneck",
			"#356: Slam Dance Dooze",
			"#357: Mariah Duckface, the Beaked Woman",
			"#358: Count Mesmerize",
			"#359: Sonny-Boy Oedipus Acre",
			"#360: Pick Mama Susan Xavier",
			"#361: Chelsea Bacon",
			"#362: Archie Axe",
			"#363: Sally Hoot-Hoot",
			"#364: Mr. Pendleton",
			"#365: Saves-Receipts Dave",
			"#366: Sir Walter British",
			"#367: Elmer, the Crankscout",
			"#368: Golden Neck",
			"#369: Marinated Alex Pons",
			"#370: El Boot",
			"#371: Shapeshifting Demon",
			"#372: Jeremiah Tip Top",
			"#373: Amanda CeeCee Strobelight",
			"#374: Irving Alva Edison, Inventor of the Hobophone",
			"#375: Leather Apron",
			"#376: Lead Apron",
			"#377: Foil Apron",
			"#378: Burnt Goathead",
			"#379: Saint Sorryass",
			"#380: Overly Familiar Fung",
			"#381: Chalmers, the Bridge Champ",
			"#382: Elephantine McMoot",
			"#383: Neekerbeeker Perry Toenz",
			"#384: Teattime BB Stiles",
			"#385: Coalie T",
			"#386: Hubbel &#8220;I Predicted Lindy Hop&#8221; Deerblind",
			"#387: Hubie Hewitt, the Broadway Legend",
			"#388: Huge Crybaby McWeepy",
			"#389: Poo-Knickers Elias",
			"#390: Elffriend Weingarten",
			"#391: Forktongue Nigel Fork",
			"#392: Woodeye Apfel",
			"#393: Hairlip Mikhail",
			"#394: Solid First Draft Patton Taylor",
			"#395: Prettynickels, the Lamb",
			"#396: Not-Only But-Also Pete",
			"#397: Penthief Hickock",
			"#398: La Grande Mel",
			"#399: Applebee O&#8217;Bennigan McFridays",
			"#400: Lardy Jerry Lardo",
			"#401: Low-Carb Aleks Stovepipe",
			"#402: Hugo Stares",
			"#403: Eldred Splinters",
			"#404: Oliver, the Train-Oyster",
			"#405: Pring, Ultralord of the Hobo Jungle",
			"#406: Utz, the Crab Chip",
			"#407: Salt-and-Pepper Chest",
			"#408: Beverly Hills Buntz",
			"#409: Mississippi Barry Phlegm",
			"#410: Matter-Eater Brad",
			"#411: 49-State Apthorp, the Alaska-Phobe",
			"#412: New Hampshire Todd",
			"#413: &#8220;Taxachusetts&#8221; Glenn",
			"#414: Hydrocephalic Jones",
			"#415: Vermont &#8220;Greenmountain Boy&#8221; Phil Marijuana",
			"#416: Alaska Mick the Crabber",
			"#417: Arizona Ludwig",
			"#418: California Ainsley Shortpants",
			"#419: Collegeboy Brainiac, the Hobo Einstein",
			"#420: Dr. Zizmor",
			"#421: Silas Swollentoe",
			"#422: Slimneck Holden Fop",
			"#423: Aspiring Jaster",
			"#424: Illinois Obama",
			"#425: Sammy Austere",
			"#426: New Mexico Anselm Turquoise-Eater",
			"#427: Caboose-Fouling Ferris Ntz",
			"#428: Prayerful Stan, the Bent-Knee Yahoo",
			"#429: Four-Fisted Jock Socko",
			"#430: Buttery-Cheeks Anton",
			"#431: Shadow (&#8220;Blinky&#8221;) Preston",
			"#432: Godigisel the Vandal",
			"#433: Gunderic Godigiselson",
			"#434: Panzo the Spiral-Cut Ham",
			"#435: Smoke-Collecting Reg",
			"#436: Hot Gnome Jimmy Jackson",
			"#437: Pontius Cornsilk-Heart",
			"#438: Sanford Who Lacks Fingerprints",
			"#439: Treesap-Covered N. Magruder",
			"#440: Thor Hammerskold, the Mexican",
			"#441: Bingo-Balls Nick Chintz",
			"#442: Bleedingtoe the Barefoot &#8217;Bo",
			"#443: Hondo &#8220;Whatever That Lizard Is That Walks on Water&#8221;",
			"#444: Salami Sunshine",
			"#445: Fourteen-Bindelstick Frank",
			"#446: Oregon Brucie Shunt",
			"#447: Pirandello, the Many-Bearded",
			"#448: Quinn and His Quaker Oats Box Drum",
			"#449: Fatneck Runt",
			"#450: my-e-hobo.com",
			"#451: Somersaulting Mike Spitz",
			"#452: Bo &#8217;Bo",
			"#453: Abelard &#8220;Sunken Treasure&#8221; Lowtrousers",
			"#454: Colin, That Cheerful Fuck",
			"#455: Battling Joe Frickinfrack",
			"#456: Monsieur Dookie, the Francophonic",
			"#457: Happy Horace Noosemaker",
			"#458: Hieronymous Crosseyes",
			"#459: Crumbjacket Timmy",
			"#460: Overload-the-Dishwasher Mac",
			"#461: Phythmic Clyde Hopp",
			"#462: Microbrew Stymie",
			"#463: El Caballo, the Spanish Steed",
			"#464: Lee Burned-Beyond-Recognition",
			"#465: Hollering Martin Mandible",
			"#466: Damien Pitchfork, the Freightyard Satan",
			"#467: Handformed Hamburger Clarence West",
			"#468: Dr. Nobel Dynamite",
			"#469: Pickled-Noggin Nettles",
			"#470: Mischievous Craig",
			"#471: Baldy Lutz, the Amityville Horror",
			"#472: Ashen Merle Buzzard",
			"#473: Frypan Nonstick McGee",
			"#474: Singleminded Hubbard",
			"#475: Maryland Sol Saynomore",
			"#476: Baked Salmon Salad Finn",
			"#477: Unshakably Morose Flo",
			"#478: Fr. Christian Irish, the Deep-Fat Friar",
			"#479: Smokestack-Hugger Jools Nygaard",
			"#480: Fossilwise Opie Fingernail",
			"#481: Tab-Collar Dix",
			"#482: George Slay, the Duck Throttler",
			"#483: Eldon Waxhat, the Waterproof Man",
			"#484: Timely Clayton, the Human Wristwatch (so named not because of his punctuality, but because one arm was significantly shorter than the other)",
			"#485: Both Dakotas Dave",
			"#486: Duke Jeremiah Choo-Choo",
			"#487: Transistorized Maximillian, the Hobo Cyborg",
			"#488: Gravelbed Gavin Astor",
			"#489: Pantless, Sockless, Shoeless Buster Bareass",
			"#490: Alternate-Dimension Bela Boost",
			"#491: Atlas Flatshoulders",
			"#492: Scurvied Leo Falsebreath",
			"#493: Toby Anchovy, the Canned Man",
			"#494: Mad Max",
			"#495: The Goose",
			"#496: Not the Goose",
			"#497: Mister Torso, the Legless Wonder",
			"#498: Jedediah Dryasdust",
			"#499: Loving Vincent Hugsalot",
			"#500: The Rambling, Rambling Boris Wander",
			"#501: Business-Class Klaus Riel",
			"#502: Emergency Exit Aisle Gustav Nook",
			"#503: Unnervingly Candid Nicky Thain",
			"#504: Snoops Lightstep Trenchcoat, the Hobo PI",
			"#505: William Carlos Williams",
			"#506: Beef Grease Porter Dripchin",
			"#507: Exoskeleton Chester Fields",
			"#508: Roth IRA Romeo Leeds, the Well Prepared",
			"#509: Bum-Smiter Phillip",
			"#510: Bum-Hating Virgil Hate-Bum",
			"#511: Thor the Bum-Hammer",
			"#512: Bum-Tolerant Brendan Sleek",
			"#513: Most Agree: It&#8217;s Kilpatrick",
			"#514: The Beloved Dale Thankyounote",
			"#515: Unpronounceable",
			"#516: Thad Malfeasance",
			"#517: Chiseltooth Muck Manly",
			"#518: Amsterdam Jocko",
			"#519: Sinister Leonard Longhair",
			"#520: Beery Clive the Eunuch",
			"#521: Chaim the Squirrelkeeper",
			"#522: Nightblind and Colorblind, the Blind Twins",
			"#523: Milosz the Anarchist Puppeteer",
			"#524: Jimmy &#8220;New Man&#8221; Neandertal",
			"#525: Lonnie Pina Colada",
			"#526: Washington State Amy Swipe",
			"#527: Gopher-State Sam, the Minnesota Man",
			"#528: Candle-Eyed Sally",
			"#529: Packrat Red and his Cart o&#8217; Sad Crap",
			"#530: Trixie of the East",
			"#531: Trixie of the West",
			"#532: Fine-Nipple Tom Bazoo",
			"#533: The Friends of Reginald McHate Society",
			"#534: Oregon Perry Hashpipe",
			"#535: Bold &#8217;n&#8217; Zesty Brad",
			"#536: Mermaid Betty Scales",
			"#537: Spotted Dick",
			"#538: Shanty Queen Elizabeth Regina",
			"#539: Nichols Crackknuckle",
			"#540: Stew Socksarewarm",
			"#541: Huge-Calves Dwight",
			"#542: A-Number-1",
			"#543: N-Number-13",
			"#544: Arthur Moonlight",
			"#545: Andrea Clarke, the Human Shark",
			"#546: Monkey&#8217;s-Paw Patterson",
			"#547: Myron Biscuitspear, the Dumpster Archeologist",
			"#548: Ollie Ebonsquirrel",
			"#549: The Classic Brett Martin",
			"#550: Douglas, the Future of Hoboing",
			"#551: Ironbelly Norton",
			"#552: Dilly Shinguards",
			"#553: Rufus Caboose",
			"#554: Rear Admiral JF Grease Pencil",
			"#555: King Cotton",
			"#556: Prince Hal Oystershuck, the Royal Shucker",
			"#557: Unconditional Gavin",
			"#558: Squirrelcloak",
			"#559: Idaho Woody Harrelson",
			"#560: Jane the Boxcar Beekeeper",
			"#561: Aaron Three-Shirts",
			"#562: Paste-Smeller Luke",
			"#563: Lowly Highley",
			"#564: Elihu Skinpockets",
			"#565: Marian May Wyomingsong",
			"#566: Stitches the Railyard Sutureman",
			"#567: Klonopin Clyde",
			"#568: Benny Twenty-Squirrels",
			"#569: Chickeny-Flavored Remy Bunk",
			"#570: Juicepockets Thomas Moone",
			"#571: Eustace Feetbeer",
			"#572: Amnesiac Jared Stringy",
			"#573: Shagrat, Orc of the Ozarks",
			"#574: Billy Butterfly Net",
			"#575: Ammonia Cocktail Jones",
			"#576: Norma Shinynickels",
			"#577: Jonathan Crouton",
			"#578: Antigone Spit",
			"#579: El Top-Hat Swindlefingers",
			"#580: PomPom the Texas Dancing Dog",
			"#581: Gin-Bucket Greg",
			"#582: Yuri Trimble, the Alien Pod-Person",
			"#583: South Carolina Sarah Lardblood",
			"#584: Bloody-Stool LaSalle",
			"#585: Pith-Helmet Andy",
			"#586: Self-Taught-Guitarist Edmund",
			"#587: Don Tomasino di Shit-the-Bed",
			"#588: Markansas",
			"#589: Neckfat JK Trestle",
			"#590: Pansy Overpass",
			"#591: Ralph Raclette Cornichon, Hobo of the Mountains",
			"#592: Montana Nbdego Tch!k",
			"#593: Unbearably Oenophilic Ned",
			"#594: Jonas Tugboy, Professional Masturbator",
			"#595: Cinderfella Dana Dane",
			"#596: Kerosene-Soaked Tom",
			"#597: Black-Bottle Priam",
			"#598: Pinprick Butell",
			"#599: Stool-Sample Frank",
			"#600: Iowa Noam Chomksy",
			"#601: Etienne, Roi of the Rapier",
			"#602: Amesy Squirrelstomper, the Chipmunk Preferrer",
			"#603: Ned Gravelshirt",
			"#604: NPR Willard Hotz, the Soothing-Voiced",
			"#605: Amen to Polly Fud",
			"#606: Constantly Sobbing Forrester",
			"#607: Maine-iac Leonid",
			"#608: Magnetized James",
			"#609: Hobo Jake Jerrold, Representing the Whole Mid-Atlantic Region",
			"#610: Jiminy Sinner",
			"#611: Pamela Chickeneggs (i.e., Hobo Caviar)",
			"#612: Chuck McKindred: Not So Holy, But Very Moley",
			"#613: Q the Quantum Man",
			"#614: Salad-Fork Ron",
			"#615: Warbling Timmy Tin Voice and His Voicebox",
			"#616: Ambassador Roasting Pan",
			"#617: Warren Smazell, Founder of Hobotics&#174;",
			"#618: Ventriloquism Jimmy and &#8220;Madam&#8221; the Talking Bean Can",
			"#619: Nosepicker Rick Pick",
			"#620: The Black Squirrel Fairy",
			"#621: Alabama Edsel Brainquake",
			"#622: Kid Silverhair, the Man of Indeterminate Age",
			"#623: Catscratch Tremont Nude",
			"#624: Bill Jaundice",
			"#625: Sugarhouse Morris the Sapper",
			"#626: Nutrition-Shake Emery",
			"#627: Nicknameless Norris Shine",
			"#628: Stinging Polly Papercuts",
			"#629: Deke Hidden Hornets&#8217; Nest",
			"#630: The Wisconsin Scourge",
			"#631: Brendan Headbristles",
			"#632: His Excellency Nooney Sockjelly",
			"#633: Whistling Anus Mecham, Le Petomaine",
			"#634: Talmidge, the Bactine Bearer",
			"#635: Tailstump Gunther, the Vestigial Man",
			"#636: The Hon. Charlie Weed-Farmer",
			"#637: Philatelist Joey Licks",
			"#638: Old Pliny Dance-for-Ham",
			"#639: Rheumy Sven",
			"#640: Wormy Glenn and Nootka the Flatworm",
			"#641: Hidalgo, the Devil Stick Artiste",
			"#642: The Fucky From Kentucky",
			"#643: Prince Bert in Exile, the Man in the Foil Mask",
			"#644: Siderodromophobic Billy",
			"#645: Antlered Calvin",
			"#646: Cambridge Massachusetts Claude",
			"#647: Cyrus the Persian Sturgeon",
			"#648: Kneepants Erasmus, the Humanist",
			"#649: Little Gavin Spittle",
			"#650: Tar-tongue Godfrey Strange",
			"#651: Honeypalms Gordon Lips",
			"#652: Luke &#8220;the Lifestyle&#8221; Dammmers",
			"#653: Simon Squirrelskin",
			"#654: Scabpicker Sandy Rump",
			"#655: Chicken Butt, Five Cents a Cut",
			"#656: Wise Solomon Babysplitter",
			"#657: Telekinetic Dave B.",
			"#658: Telekinetic Dave F.",
			"#659: Whiskeyblood Willie Sot",
			"#660: Unger and his Duststorm Bride",
			"#661: Zachary Goatflirter",
			"#662: &#8220;La Grippe&#8221;",
			"#663: Uranus John, the Star-Traveler",
			"#664: Accusin&#8217; Tim Dunn",
			"#665: Tennessee Linthelmet",
			"#666: The Unformed Twin of Tennessee Linthelmet",
			"#667: Turkeyballs Paco",
			"#668: Andre the Indianapolist",
			"#669: Wally Dregs, the Newfoundland Screech",
			"#670: Flaky Mike Psoriasis",
			"#671: Hell&#8217;s Own Breath Hinkley",
			"#672: Gerald Chapcheeks",
			"#673: Acid-Saliva Curly Stokes",
			"#674: Oklahoma Stilgar",
			"#675: Rocky Shitstain Mankowicz",
			"#676: Rocky Shitstain Mankowicz Part II, the Quickening",
			"#677: Professor &#937;",
			"#678: Sanitized-for-Your-Protection Eddie Summers",
			"#679: Jan, the Jager-Meister",
			"#680: Big-Tipper Silas Fake-Nickel",
			"#681: Anaerobic Eben Stiles",
			"#682: Replicant Wemberly Plastiskin and his Clockwork Squirrel &#8220;Toothy&#8221;",
			"#683: Harry Coughblood",
			"#684: Aesop Bedroll, the Fluffy Pillow Man",
			"#685: Widow-Kisser Roger",
			"#686: Experimental Hobo Infiltration Droid &#8220;41-K&#8221;",
			"#687: Baron Bayonet, the Bull-Sticker",
			"#688: Mikey Gluesniff",
			"#689: Bell&#8217;s-Palsy Brennan",
			"#690: Chiptooth Berman, the Bottle Biter",
			"#691: Undertaker Robert, the Lint-Coffin Weaver",
			"#692: Betty, the Exorcist",
			"#693: Tittytwister Blake Horrid",
			"#694: Mallory Many-Bruises",
			"#695: Mad or Sad Judd (no one can tell)",
			"#696: Troglodytic Amory Funt",
			"#697: Smokehouse &#8220;Frankie&#8221; Jowl-Poker",
			"#698: Utility-Belt Deana",
			"#699: The Unshakable Will of Wade Terps",
			"#700: Trainwhistle Ernie Roosevelt, the President&#8217;s Long-Lost Brother",
	);
	
	// Pick the key for a random entry
	$randhobo = array_rand($hobos, 1);
	
	// Assigns value from randomly selected key to $showhobo
	$showhobo = $hobos[$randhobo];
	
	// Returns value with optional wrap tag and class
	return ($wraptag) ? doTag ($showhobo, $wraptag, $class) : $showhobo;

}

# --- END PLUGIN CODE ---

?>