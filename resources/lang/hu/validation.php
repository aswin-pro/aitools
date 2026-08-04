<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    [
        "accepted" => "Az attribútumot elfogadni kell.",
        "accepted_if" => "Az attribútumot akkor kell elfogadni, amikor: Egyéb: Érték.",
        "active_url" => "Az: az attribútum nem érvényes URL.",
        "after" => "Az attribútumnak dátumnak kell lennie: dátum.",
        "after_or_equal" => "Az attribútumnak dátumnak kell lennie, vagy azzal egyenlőnek kell lennie: dátum.",
        "alpha" => "Az: az attribútumnak csak betűket kell tartalmaznia.",
        "alpha_dash" => "Az attribútumnak csak betűket, számokat, kötőjeleket és aláhúzást tartalmazhat.",
        "alpha_num" => "Az attribútumnak csak betűket és számokat tartalmazhat.",
        "array" => "Az attribútumnak tömbnek kell lennie.",
        "before" => "Az attribútumnak dátumnak kell lennie: dátum.",
        "before_or_equal" => "Az attribútumnak dátumnak kell lennie, vagy azzal egyenlőnek kell lennie: dátum.",
        "between" => [
            "numeric" => "Az attribútumnak a következőnek kell lennie: min és: max.",
            "file" => "Az attribútumnak a következőnek kell lennie: min és: max kilobájt.",
            "string" => "Az attribútumnak a következőnek kell lennie: min és: max karakterek.",
            "array" => "Az attribútumnak a következőnek kell lennie: min és: max elemek."
        ],
        "boolean" => "Az attribútummezőnek igaznak vagy hamisnak kell lennie.",
        "confirmed" => "A: Attribútum megerősítése nem egyezik.",
        "current_password" => "A jelszó helytelen.",
        "date" => "Az: az attribútum nem érvényes dátum.",
        "date_equals" => "Az attribútumnak dátumnak kell lennie: dátummal.",
        "date_format" => "Az: az attribútum nem felel meg a formátumnak: formátum.",
        "declined" => "Az attribútumot el kell utasítani.",
        "declined_if" => "Az attribútumot el kell utasítani, amikor: Egyéb: Érték.",
        "different" => "A: attribútum és: másoknak másnak kell lennie.",
        "digits" => "A: attribútumnak kell lennie: számjegyek száma.",
        "digits_between" => "Az attribútumnak a következőnek kell lennie: min és: max számjegyek.",
        "dimensions" => "Az: az attribútum érvénytelen képméretekkel rendelkezik.",
        "distinct" => "Az: Attribútummező duplikált értéke van.",
        "email" => "Az attribútumnak érvényes e -mail címnek kell lennie.",
        "ends_with" => "A: Az attribútumnak az alábbiak egyikével kell véget érnie:: értékek.",
        "enum" => "A kiválasztott: Az attribútum érvénytelen.",
        "exists" => "A kiválasztott: Az attribútum érvénytelen.",
        "file" => "Az: az attribútumnak fájlnak kell lennie.",
        "filled" => "A: Az attribútummezőnek értékkel kell rendelkeznie.",
        "gt" => [
            "numeric" => "Az attribútumnak nagyobbnak kell lennie, mint: érték.",
            "file" => "Az attribútumnak nagyobbnak kell lennie, mint: érték kilobájt.",
            "string" => "Az attribútumnak nagyobbnak kell lennie, mint: Értékkarakterek.",
            "array" => "Az attribútumnak többnek kell lennie, mint: Értékelemek."
        ],
        "gte" => [
            "numeric" => "Az attribútumnak nagyobbnak vagy egyenlőnek kell lennie: érték.",
            "file" => "Az attribútumnak nagyobbnak vagy egyenlőnek kell lennie: Érték kilobájt.",
            "string" => "Az attribútumnak nagyobb vagy egyenlőnek kell lennie: Értékkarakterek.",
            "array" => "Az attribútumnak rendelkeznie kell: Értékelők vagy több."
        ],
        "image" => "Az attribútumnak képnek kell lennie.",
        "in" => "A kiválasztott: Az attribútum érvénytelen.",
        "in_array" => "Az: az attribútummező nem létezik: Egyéb.",
        "integer" => "Az attribútumnak egész számnak kell lennie.",
        "ip" => "Az attribútumnak érvényes IP -címnek kell lennie.",
        "ipv4" => "Az attribútumnak érvényes IPv4 -címnek kell lennie.",
        "ipv6" => "Az: az attribútumnak érvényes IPv6 -címnek kell lennie.",
        "json" => "Az: az attribútumnak érvényes JSON karakterláncnak kell lennie.",
        "lt" => [
            "numeric" => "Az attribútumnak kevesebbnek kell lennie: érték.",
            "file" => "Az attribútumnak kevesebbnek kell lennie: érték kilobájtok.",
            "string" => "Az attribútumnak kevesebbnek kell lennie, mint: Értékkarakterek.",
            "array" => "Az attribútumnak kevesebbnek kell lennie, mint: Értékelemek."
        ],
        "lte" => [
            "numeric" => "Az attribútumnak kevesebbnek vagy egyenlőnek kell lennie: érték.",
            "file" => "Az attribútumnak kevesebbnek kell lennie vagy egyenlőnek kell lennie: Érték kilobájt.",
            "string" => "Az attribútumnak kevesebbnek kell lennie vagy egyenlőnek kell lennie: Értékkarakterek.",
            "array" => "Az: az attribútumnak nem lehet több, mint: Értékelemek."
        ],
        "mac_address" => "Az attribútumnak érvényes MAC -címnek kell lennie.",
        "max" => [
            "numeric" => "Az: az attribútum nem lehet nagyobb, mint: max.",
            "file" => "Az attribútum nem lehet nagyobb, mint: max kilobájt.",
            "string" => "Az: az attribútum nem lehet nagyobb, mint: Max karakterek.",
            "array" => "Az: az attribútumnak nem lehet több, mint: max elemek."
        ],
        "mimes" => "Az: az attribútumnak típusú fájlnak kell lennie:: értékek.",
        "mimetypes" => "Az: az attribútumnak típusú fájlnak kell lennie:: értékek.",
        "min" => [
            "numeric" => "Az attribútumnak legalább: min.",
            "file" => "Az attribútumnak legalább: min kilobájtnak kell lennie.",
            "string" => "Az attribútumnak legalább: min karaktereknek kell lennie.",
            "array" => "Az attribútumnak legalább: min tételeknek kell lennie."
        ],
        "multiple_of" => "Az: az attribútumnak többszörösnek kell lennie: érték.",
        "not_in" => "A kiválasztott: Az attribútum érvénytelen.",
        "not_regex" => "Az: attribútum formátuma érvénytelen.",
        "numeric" => "Az attribútumnak számnak kell lennie.",
        "password" => "A jelszó helytelen.",
        "present" => "Az attribútum mezőnek jelen kell lennie.",
        "prohibited" => "Az attribútum mező tilos.",
        "prohibited_if" => "A: Az attribútum mező tilos, amikor: Egyéb: érték: Érték.",
        "prohibited_unless" => "Az attribútummező tilos, hacsak más van: az értékek: értékek.",
        "prohibits" => "A: Attribútummező tiltja: Egyéb attól, hogy jelen legyen.",
        "regex" => "Az: attribútum formátuma érvénytelen.",
        "required" => "A: attribútum mező szükséges.",
        "required_array_keys" => "A: Az attribútum mezőnek tartalmaznia kell a következőket:: értékek.",
        "required_if" => "A: attribútummezőre van szükség, amikor: Egyéb: Érték.",
        "required_unless" => "A következő: az attribútummezőre, hacsak nem: egyéb van: Értékek.",
        "required_with" => "Az attribútummező akkor szükséges, amikor: az értékek jelen vannak.",
        "required_with_all" => "Az attribútummező akkor szükséges, amikor: értékek vannak jelen.",
        "required_without" => "Az attribútummező akkor szükséges, amikor: az értékek nincsenek jelen.",
        "required_without_all" => "Az attribútum mezőre szükség van, ha egyik sem: az értékek jelen vannak.",
        "same" => "A: attribútum és: másnak meg kell egyeznie.",
        "size" => [
            "numeric" => "Az attribútumnak: méretnek kell lennie.",
            "file" => "Az attribútumnak: méretű kilobájtnak kell lennie.",
            "string" => "Az attribútumnak kell lennie: méret karakterek.",
            "array" => "Az attribútumnak tartalmaznia kell: méret tételeket."
        ],
        "starts_with" => "Az: az attribútumnak az alábbiak egyikével kell kezdődnie:: értékek.",
        "string" => "Az: az attribútumnak karakterláncnak kell lennie.",
        "timezone" => "Az: az attribútumnak érvényes időzónának kell lennie.",
        "unique" => "Az: az attribútumot már megtették.",
        "uploaded" => "Az: az attribútumot nem sikerült feltölteni.",
        "url" => "Az: az attribútumnak érvényes URL -nek kell lennie.",
        "uuid" => "Az attribútumnak érvényes UUID -nak kell lennie.",
        "custom" => [
            "attribute-name" => [
                "rule-name" => "egyéni utasítás"
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
