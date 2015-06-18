<?php

function getArpabetToSpelling() 
{
    return [
        'AA' => 'o',
        'AE' => 'a',
        'AH' => 'uh',
        'AO' => 'aw',
        'AW' => 'ou',
        'AY' => 'ahy',
        'B' => 'b',
        'CH' => 'ch',
        'D' => 'd',
        'DH' => 'th',
        'EH' => 'e',
        'ER' => 'ur',
        'EY' => 'ey',
        'F' => 'f',
        'G' => 'g',
        'HH' => 'h',
        'IH' => 'i',
        'IY' => 'ee',
        'JH' => 'j',
        'K' => 'k',
        'L' => 'l',
        'M' => 'm',
        'N' => 'n',
        'NG' => 'ng',
        'OW' => 'oh',
        'OY' => 'oi',
        'P' => 'p',
        'R' => 'r',
        'S' => 's',
        'SH' => 'sh',
        'T' => 't',
        'TH' => 'th',
        'UH' => 'uu',
        'UW' => 'oo',
        'V' => 'v',
        'W' => 'w',
        'Y' => 'y',
        'Z' => 'z',
        'ZH' => 'zh'
    ];
}

function getArpabetToIpa() 
{
    return [
        'AA' => 'ɑ',
        'AE' => 'æ',
        'AH' => 'ʌ',
        'AO' => 'ɔ',
        'AW' => 'aʊ',
        'AY' => 'aɪ',
        'B' => 'b',
        'CH' => 'tʃ',
        'D' => 'd',
        'DH' => 'ð',
        'EH' => 'ɛ',
        'ER' => 'ɝ',
        'EY' => 'eɪ',
        'F' => 'f',
        'G' => 'g',
        'HH' => 'h',
        'IH' => 'ɪ',
        'IY' => 'i',
        'JH' => 'dʒ',
        'K' => 'k',
        'L' => 'ɫ',
        'M' => 'm',
        'N' => 'n',
        'NG' => 'ŋ',
        'OW' => 'oʊ',
        'OY' => 'ɔɪ',
        'P' => 'p',
        'R' => 'r',
        'S' => 's',
        'SH' => 'ʃ',
        'T' => 't',
        'TH' => 'θ',
        'UH' => 'ʊ',
        'UW' => 'u',
        'V' => 'v',
        'W' => 'w',
        'Y' => 'j',
        'Z' => 'z',
        'ZH' => 'ʒ'
    ];
}

function getConsonantBlends()
{
    $consonant_blends = [
        'bl' => 'bl', 
        'br' => 'br', 
        'ch' => 'ch', 
        'ck' => 'k', 
        'cl' => 'kl', 
        'cr' => 'kr', 
        'dr' => 'dr', 
        'fl' => 'fl', 
        'fr' => 'fr', 
        'gh' => 'g', 
        'gl' => 'gl', 
        'gr' => 'gr', 
        'ng' => 'ng', 
        'ph' => 'f', 
        'pl' => 'pl', 
        'pr' => 'pr', 
        'qu' => 'kw', 
        'sc' => 'sk', 
        'sh' => 'sh', 
        'sk' => 'sk', 
        'sl' => 'sl', 
        'sm' => 'sm', 
        'sn' => 'sn', 
        'sp' => 'sp', 
        'st' => 'st', 
        'sw' => 'sw', 
        'th' => 'th', 
        'tr' => 'tr', 
        'tw' => 'tw', 
        'wh' => 'w', 
        'wr' => 'r',
        'nth' => 'nth', 
        'sch' => 'sk', 
        'scr' => 'skr', 
        'shr' => 'shr', 
        'spl' => 'spl', 
        'spr' => 'spr', 
        'squ' => 'sqw', 
        'str' => 'str', 
        'thr' => 'thr'
    ];
}

function getPrefixes() {
    return [
        'dis' => 'dis',
        'mis' => 'mis',
        'un' => 'uhn',
        'be' => 'bee',
        'de' =>'dee',
        'ex' => 'eks',
        're' => 'ree'
    ];
}

function getSuffixes() {
    return [
        'ness',
        'ful',
        'ing'
    ];
}