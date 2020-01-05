<?php

function get_rooms()
{
    # TODO: backend
    return [
        0 => [
            "title" => "Семинарна зала",
            "img_url" => "seminars-hall.jpg",
            "color" => "dodgerblue",
        ],
        1 => [
            "title" => "Зала за колективна работа",
            "img_url" => "collective-work-hall.jpg",
            "color" => "hotpink",
        ],
    ];
}

function get_room($id) {
    # TODO: backend
    return get_rooms()[$id];
}
