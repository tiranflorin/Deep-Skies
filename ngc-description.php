<?php
session_start();
$sPageTitle = "NGC Objects Description | Deep-Skies";
require_once('php/views/header.php');
?>

<body>
<div id="wrap">

<?php
require_once('php/views/horizontalNavigation.php');
require_once('php/views/ngc-description/jumbotron.php');
?>

    <div class="container">
        <div class="row">
            <div class="col-lg-2">
            </div>
            <div class='col-lg-8'>
        <pre>
The descriptions use the abbreviations from the original NGC and Burnham's.  They are given below:

!    remarkable object                 !!   very remarkable object
am   among                             n    north
att  attached                          N    nucleus
bet  between                           neb  nebula, nebulosity
B    bright                            P w  paired with
b    brighter                          p    pretty (before F,B,L,S)
C    compressed                        p    preceding
c    considerably                      P    poor
Cl   cluster                           R    round
D    double                            Ri   rich
def  defined                           r    not well resolved
deg  degrees                           rr   partially resolved
diam diameter                          rrr  well resolved
dif  diffuse                           S    small
E    elongated                         s    suddenly
e    extremely                         s    south
er   easily resolved                   sc   scattered
F    faint                             susp suspected
f    following                         st   star or stellar
g    gradually                         v    very
iF   irregular figure                  var  variable
inv  involved                          nf   north following
irr  irregular                         np   north preceding
L    large                             sf   south following
l    little                            sp   south preceding
mag  magnitude                         11m  11th magnitude
M    middle                            8... 8th mag and fainter
m    much                              9...13  9th to 13th magnitude

If you have never dealt with the NGC abbreviations before, perhaps a few examples will help.

NGC#     Description            Decoded descriptions

214   pF, pS, lE, gvlbM   pretty faint, pretty small,
                          little elongated, gradually very
                          little brighter in the middle

708   vF, vS, R           very faint, very small, round

891   B, vL, vmE          bright, very large, very much elongated

7009  !, vB, S            remarkable object, very bright, small

7089  !! B, vL, mbM       extremely remarkable object, bright, very
      rrr, stars mags     large, much brighter middle, resolved,
      13.....             stars 13th magnitude and dimmer

2099  !  B, vRi, mC       remarkable object, bright, very rich,
                          much compressed

6643  pB,pL,E50,2 st p    pretty bright, pretty large,
                          elongated in position angle 50 degrees,
                          two stars preceding
        </pre>
                    </div>
            <div class="col-lg-2">
                    </div>
            </div>
            </div>
            <!-- /container -->

        </div>
        <!-- /wrap -->
<?php
require_once('php/views/footer.php');
?>