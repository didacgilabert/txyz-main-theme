# txyz-main-theme

Tema de blocs del lloc principal de troposfera.xyz, amb edició completa del
lloc. Ve de `txyz-main-theme`, que es queda al costat com a còpia de
seguretat i fora del repositori fins que aquest estigui actiu al WordPress.

## Requisits

WordPress 6.7 o superior i PHP 7.2 o superior.

## Com està muntat

```
theme.json      tokens de tipografia, color i espaiat
style.css       capçalera del tema i estils d'arrel
functions.php
templates/      8 plantilles (front-page, page, single, archive, search, 404)
parts/          8 parts (capçaleres, peus, barra lateral)
patterns/       100 patterns
styles/         variacions d'estil, repartides en blocks, colors, sections
                i typography
inc/            13 fitxers de PHP
assets/         71 fitxers
```

Les variacions de `styles/` són el lloc on han d'anar les decisions visuals que
es repeteixen. Cadascuna deixa una classe al contingut i res més, de manera que
canviar-la des d'aquí arriba als quatre idiomes alhora sense tocar cap pàgina.

## Publicar un canvi

Cada canvi que hagi de sortir publicat vol que es pugi el `Version:` de la
capçalera de `style.css`. WordPress compara aquest número amb el que té
instal·lat, i sense pujar-lo no detecta que hi ha res nou.

## Sobre els noms

El nom de la carpeta va pla perquè els scripts i el desplegament no s'ennuegin
amb espais ni caràcters especials. El `»` viu al `Theme Name:` de la capçalera,
que és el que es veu a l'escriptori de WordPress.

El `Text Domain:` encara diu `txyz-main-theme`. Canviar-lo vol repassar
abans si el tema té textos traduïts que hi apuntin.
