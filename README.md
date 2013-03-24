VizHash GD
==========

What is a visual hash ?
=======================

MD5 and SHA1 are common hashing function, which produce a binary or hex string. A visual hash works the same, but produces an image.

Like MD5 or SHA1:

* It takes an arbitrary, variable-size input.
* It's a one-way function.
* The image is unique to the input string (it's a fingerprint)
* A single bit of difference in the input string produces a totally different image.
* It's not possible to deduce the input string from the image.

Applications
============

* *File comparison*: If you need to compare files or commit hashes, comparing visual hashes is instant (much easier than comparing md5 or sha1).

* *Avatars*: VizHash can be used as an avatar in forums or blog comments. Simply hash the IP (or email) address and feed into VizHash and ou have a unique icon for each visitor, specific to its IP or email address. VizHash is used is [ZeroBin](https://github.com/sebsauvage/ZeroBin) discussions ([example](http://sebsauvage.net/paste/?b3d94022c526d402#T0faEej4u5xubp7d2+n9Xg1tIogj5McY99zP8D0120U)).

* *Protection against TabJacking*: VizHash could be used - for example - as a persona in Firefox to give a visual hint of the real domain the user is currently on.

* *Password check* : Make sure you typed the right password without displaying it on screen (à la Lotus Notes).

What is VizHash GD ?
====================

VisHash GD is an implementation of a visual hash in php. It is free software, under the zlib/libpng OSI licence.

Features:

* Can produce images up to 256×256.
* Visual hashes keep their visual features even if scaled (see examples below)
* Uses only php and basic GD (which are available almost everywhere). Does not use imagefilter GD functions (which are not available everywhere).
* Runs under php4 and php5.
* VizHash GD is not beautiful (no fractals, wavelets or high-end filters). It's designed to be fast, light on CPU and to produce images which are easy to differentiate.

You can [test it online](http://sebsauvage.net/vizhash_gd.php).

Other implementations
=====================

There are implementation of VizHash in other languages:

* Javascript version (by Sam & Max): https://github.com/sametmax/VizHash.js ([demo](http://jsfiddle.net/2nYsg/3/embedded/result/))
* jQuery password plugin (by Sam & Max): https://github.com/sametmax/jQuery-Visual-Password ([demo](http://jsfiddle.net/TANLB/light/))
* Java (by inouire):  http://codingteam.net/project/inouire_mini_projects/browse/VizHash4j

Note that these visual hashes are *close* to the php implementation, but may have some differences. 

------------------------------------------------------------------------------

VizHash GD is under the zlib/libpng licence:

This software is provided 'as-is', without any express or implied warranty.
In no event will the authors be held liable for any damages arising from 
the use of this software.

Permission is granted to anyone to use this software for any purpose, 
including commercial applications, and to alter it and redistribute it 
freely, subject to the following restrictions:

    1. The origin of this software must not be misrepresented; you must 
       not claim that you wrote the original software. If you use this 
       software in a product, an acknowledgment in the product documentation
       would be appreciated but is not required.

    2. Altered source versions must be plainly marked as such, and must 
       not be misrepresented as being the original software.

    3. This notice may not be removed or altered from any source distribution.

------------------------------------------------------------------------------