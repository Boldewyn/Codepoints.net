<!-- codepoint -->
<p><?php
    $plane = $codepoint->getPlane();
    printf(__('U+%04X was added to Unicode in version %s (%s). It belongs to the block %s in the %s.'),
        $codepoint->getId(),
        '<a rel="nofollow" href="'.q($router->getUrl('search?age='.$props['age'])).'">'.
        q($info->getLabel('age', $props['age'])).'</a>',
        $info->getYearForAge($props['age']),
        $block? _bl($block):'-',
        '<a class="pl" href="'.q($router->getUrl($plane)).'">'.q($plane->name).'</a>');

    if ($props['Dep']) {
        echo ' ';
        printf(__('This codepoint is %sdeprecated%s.'),
        '<a rel="nofollow" href="'.q($router->getUrl('search?Dep=1')).'">', '</a>');
    }
?></p>

<!-- character -->
<p><?php
    if ($props['sc'] === 'Zyyy') {
        printf(__('This character is a %s and is %scommonly%s used, that is, in no specific script.'),
            '<a rel="nofollow" href="'.q($router->getUrl('search?gc='.$props['gc'])).'">'.
            q($info->getLabel('gc', $props['gc'])).'</a>',
            '<a rel="nofollow" href="'.q($router->getUrl('search?sc='.$props['sc'])).'">',
            '</a>');
    } elseif ($props['sc'] === 'Zinh') {
        printf(__('This character is a %s and %sinherits%s its %sscript property%s from the preceding character.'),
            '<a rel="nofollow" href="'.q($router->getUrl('search?gc='.$props['gc'])).'">'.
            q($info->getLabel('gc', $props['gc'])).'</a>',
            '<a rel="nofollow" href="'.q($router->getUrl('search?sc='.$props['sc'])).'">',
            '</a>',
            '<span class="gl" data-term="sc">',
            '</span>');
    } else {
        printf(__('This character is a %s and is mainly used in the %s script.'),
            '<a rel="nofollow" href="'.q($router->getUrl('search?gc='.$props['gc'])).'">'.
            q($info->getLabel('gc', $props['gc'])).'</a>',
            '<a rel="nofollow" href="'.q($router->getUrl('search?sc='.$props['sc'])).'">'.
            q($info->getLabel('sc', $props['sc'])).'</a>');
    }

    $buf=array();
    foreach(explode(' ', $props['scx']) as $sc) {
        if ($sc !== $props['sc']) {
            $buf[] = '<a rel="nofollow" href="'.q($router->getUrl('search?scx='.$props['scx'])).'">'.
                    q($info->getLabel('sc', $sc)).'</a>';
        }
    }

    if (count($buf)) {
        echo ' ';
        printf(__('It is also used in the script%s %s.'),
            (count($buf) > 1)? 's' : '',
            join(', ', $buf));
    }

    $defn = $codepoint->getProp('kDefinition');
    if ($defn) {
        echo ' ';
        printf(__('The Unihan Database defines it as <em>%s</em>.'),
            preg_replace_callback('/U\+([0-9A-F]{4,6})/', function($m) {
                $router = Router::getRouter();
                $db = $router->getSetting('db');
                return _cp(Codepoint::getCP(hexdec($m[1]), $db), '', 'min');
            }, $defn));
    }

    $pronunciation = $codepoint->getPronunciation();
    if ($pronunciation) {
        echo ' ';
        printf(__('Its Pīnyīn pronunciation is <em>%s</em>.'), q($pronunciation));
    }

    if($props['nt'] !== 'None') {
        echo ' ';
        printf(__('The codepoint has the %s value %s.'),
        '<a rel="nofollow" href="'.q($router->getUrl('search?nt='.$props['nt'])).'">'.
        q($info->getLabel('nt', $props['nt'])).'</a>',
        '<a rel="nofollow" href="'.q($router->getUrl('search?nv='.$props['nv'])).'">'.
        q($info->getLabel('nv', $props['nv'])).'</a>');
    }

    $hasUC = ($props['uc'] && (is_array($props['uc']) || $props['uc']->getId() != $codepoint->getId()));
    $hasLC = ($props['lc'] && (is_array($props['lc']) || $props['lc']->getId() != $codepoint->getId()));
    $hasTC = ($props['tc'] && (is_array($props['tc']) || $props['tc']->getId() != $codepoint->getId()));
    echo ' ';
    if ($hasUC || $hasLC || $hasTC) {
        printf(__('It is related to %s%s%s%s%s.'),
            ($hasUC)? sprintf(__('its uppercase variant %s'), _cp($props['uc'], '', 'min')) : '',
            ($hasLC && $hasUC)? (($hasTC)? __(', ') : __(' and ')) : '',
            ($hasLC)? sprintf(__('its lowercase variant %s'), _cp($props['lc'], '', 'min')) : '',
            ($hasTC && ($hasUC || $hasLC))? __(' and ') : '',
            ($hasTC)? sprintf(__('its titlecase variant %s'), _cp($props['tc'], '', 'min')) : ''
        );
    }

    $info_alias = array_values(array_filter($codepoint->getALias(), function($v) {
        return $v['type'] === 'alias';
    }));
    if (count($info_alias)) {
        $_aliases = '';
        for ($i = 0, $j = count($info_alias); $i < $j; $i++) {
            if ($i > 0) {
                if ($i === $j - 1) {
                    $_aliases .= __(' and ');
                } else {
                    $_aliases .= __(', ');
                }
            }
            $_aliases .= '<em>'.q($info_alias[$i]['alias']).'</em>';
        }
        echo ' ';
        printf(__('The character is also known as %s.'), $_aliases);
    }
?></p>

<!-- glyph -->
<p><?php

    if ($props['dt'] === 'none') {
        printf(__('The glyph is %snot a composition%s.'),
            '<a rel="nofollow" href="'.q($router->getUrl('search?dt=none')).'">',
            '</a>');
    } else {
        printf(__('The glyph is a %s composition of the glyphs %s.'),
            '<a rel="nofollow" href="'.q($router->getUrl('search?dt='.$props['dt'])).'">'.
            q($info->getLabel('dt', $props['dt'])).'</a>',
            _cp($props['dm'], '', 'min'));
    }

    echo ' ';
    printf(__('It has a %s %s.'),
        '<a rel="nofollow" href="'.q($router->getUrl('search?ea='.$props['ea'])).'">'.
        q($info->getLabel('ea', $props['ea'])).'</a>',
        q($info->getCategory('ea')));

    if ($props['Bidi_M']) {
        echo ' ';
        printf(__('In bidirectional context it acts as %s and is %smirrored%s.'),
            '<a rel="nofollow" href="'.q($router->getUrl('search?bc='.$props['bc'])).'">'.
            q($info->getLabel('bc', $props['bc'])).'</a>',
            '<a rel="nofollow" href="'.q($router->getUrl('search?bc='.$props['bc'].'&bm='.
            (int)$props['Bidi_M'])).'">',
            '</a>'
        );
    } else {
        echo ' ';
        printf(__('In bidirectional context it acts as %s and is %snot mirrored%s.'),
            '<a rel="nofollow" href="'.q($router->getUrl('search?bc='.$props['bc'])).'">'.
            q($info->getLabel('bc', $props['bc'])).'</a>',
            '<a rel="nofollow" href="'.q($router->getUrl('search?bc='.$props['bc'].'&bm='.
            (int)$props['Bidi_M'])).'">',
            '</a>'
        );
    }

    if (array_key_exists('bmg', $props) &&
        $props['bmg']->getId() != $codepoint->getId()) {
        echo ' ';
        printf(__('Its corresponding mirrored glyph is %s.'), _cp($props['bmg'], '', 'min'));
    }

    if (count($confusables)) {
        echo ' ';
        printf(__('The glyph can, under circumstances, be confused with %s%d other glyphs%s.'),
               '<a href="#confusables" rel="internal">', count($confusables), '</a>');
    }

    echo ' ';
    printf(__('In text U+%04X behaves as %s regarding line breaks. It has
        type %s for sentence and %s for word breaks. The %s is %s.'),
        $codepoint->getId(),
        '<a rel="nofollow" href="'.q($router->getUrl('search?lb='.$props['lb'])).'">'.
        q($info->getLabel('lb', $props['lb'])).'</a>',
        '<a rel="nofollow" href="'.q($router->getUrl('search?SB='.$props['SB'])).'">'.
        q($info->getLabel('SB', $props['SB'])).'</a>',
        '<a rel="nofollow" href="'.q($router->getUrl('search?WB='.$props['WB'])).'">'.
        q($info->getLabel('WB', $props['WB'])).'</a>',
            q($info->getCategory('GCB')),
        '<a rel="nofollow" href="'.q($router->getUrl('search?GCB='.$props['GCB'])).'">'.
        q($info->getLabel('GCB', $props['GCB'])).'</a>');
?></p>

<?php
    /* show additional information, if any is present: */
    $ext_file = sprintf('%s/../../data/U+%04X.%s.md', __DIR__, $codepoint->getId(), $lang);
    if (is_file($ext_file)) {
        $parser = new \Michelf\Markdown;
        $parser->empty_element_suffix = ">";
        $parser->url_filter_func = function($url) {
            if (substr($url, 0, 3) === 'cp:') {
                $url = '/'.substr($url, 3);
            }
            return $url;
        };
        echo $parser->transform(file_get_contents($ext_file));
    }
?>

<!-- Wikipedia -->
<?php
$wikilang = $lang;
$wikiabstract = $codepoint->getLocalizedAbstract($lang);
if ($wikilang !== 'en' && ! $wikiabstract) {
    $wikilang = 'en';
    $wikiabstract = $codepoint->getLocalizedAbstract('en');
}
if ($wikiabstract):?>
  <p><?php printf(__('The %sWikipedia%s has the following information about this codepoint:'), '<a href="https://'.$wikilang.'.wikipedia.org/wiki/%'.q($codepoint->getRepr('UTF-8', '%')).'">', '</a>')?></p>
  <blockquote cite="https://<?php e($wikilang)?>.wikipedia.org/wiki/%<?php e($codepoint->getRepr('UTF-8', '%'))?>">
    <?php echo strip_tags($wikiabstract, '<p><b><strong class="selflink"><strong><em><i><var><sup><sub><tt><ul><ol><li><samp><small><hr><h2><h3><h4><h5><dfn><dl><dd><dt><u><abbr><big><blockquote><br><center><del><ins><kbd>')?>
  </blockquote>
<?php endif?>
