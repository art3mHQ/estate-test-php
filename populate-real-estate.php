<?php
// Sample data for real estate objects
$buildings = array(
    array(
        'title' => 'ЖК Київський',
        'content' => 'Сучасний житловий комплекс у центрі міста',
        'district' => 'Печерський',
        'building_name' => 'ЖК Київський',
        'coordinates' => '50.4501, 30.5234',
        'floors_count' => '12',
        'building_type' => 'brick',
        'eco_rating' => '4',
        'rooms' => array(
            array('room_area' => 65, 'rooms_count' => '2', 'balcony' => 'yes', 'bathroom' => 'yes'),
            array('room_area' => 45, 'rooms_count' => '1', 'balcony' => 'no', 'bathroom' => 'yes'),
        )
    ),
    array(
        'title' => 'Будинок на Хрещатику',
        'content' => 'Елітний будинок в історичному центрі',
        'district' => 'Шевченківський',
        'building_name' => 'Хрещатик 15',
        'coordinates' => '50.4482, 30.5256',
        'floors_count' => '8',
        'building_type' => 'brick',
        'eco_rating' => '3',
        'rooms' => array(
            array('room_area' => 120, 'rooms_count' => '4', 'balcony' => 'yes', 'bathroom' => 'yes'),
            array('room_area' => 85, 'rooms_count' => '3', 'balcony' => 'yes', 'bathroom' => 'yes'),
        )
    ),
    array(
        'title' => 'ЖК Оазис',
        'content' => 'Комфортне житло з розвиненою інфраструктурою',
        'district' => 'Подільський',
        'building_name' => 'ЖК Оазис',
        'coordinates' => '50.4676, 30.5326',
        'floors_count' => '16',
        'building_type' => 'panel',
        'eco_rating' => '4',
        'rooms' => array(
            array('room_area' => 55, 'rooms_count' => '2', 'balcony' => 'yes', 'bathroom' => 'yes'),
            array('room_area' => 75, 'rooms_count' => '3', 'balcony' => 'yes', 'bathroom' => 'yes'),
        )
    ),
    array(
        'title' => 'Зелений квартал',
        'content' => 'Екологічно чистий район з парками',
        'district' => 'Оболонський',
        'building_name' => 'Зелений квартал А1',
        'coordinates' => '50.5138, 30.4988',
        'floors_count' => '9',
        'building_type' => 'foam_block',
        'eco_rating' => '5',
        'rooms' => array(
            array('room_area' => 70, 'rooms_count' => '2', 'balcony' => 'yes', 'bathroom' => 'yes'),
            array('room_area' => 90, 'rooms_count' => '3', 'balcony' => 'yes', 'bathroom' => 'yes'),
        )
    ),
    array(
        'title' => 'Новобудова на Позняках',
        'content' => 'Сучасний будинок з панорамними вікнами',
        'district' => 'Дарницький',
        'building_name' => 'Позняки Резиденс',
        'coordinates' => '50.4087, 30.6354',
        'floors_count' => '20',
        'building_type' => 'panel',
        'eco_rating' => '4',
        'rooms' => array(
            array('room_area' => 48, 'rooms_count' => '1', 'balcony' => 'yes', 'bathroom' => 'yes'),
            array('room_area' => 62, 'rooms_count' => '2', 'balcony' => 'yes', 'bathroom' => 'yes'),
        )
    ),
    array(
        'title' => 'ЖК Комфорт',
        'content' => 'Доступне житло для молодих сімей',
        'district' => 'Солом\'янський',
        'building_name' => 'ЖК Комфорт',
        'coordinates' => '50.4275, 30.4632',
        'floors_count' => '14',
        'building_type' => 'panel',
        'eco_rating' => '3',
        'rooms' => array(
            array('room_area' => 52, 'rooms_count' => '1', 'balcony' => 'no', 'bathroom' => 'yes'),
            array('room_area' => 68, 'rooms_count' => '2', 'balcony' => 'yes', 'bathroom' => 'yes'),
        )
    ),
    array(
        'title' => 'Елітний комплекс',
        'content' => 'Преміум житло з консьєрж-сервісом',
        'district' => 'Печерський',
        'building_name' => 'Печерський Хіллс',
        'coordinates' => '50.4263, 30.5383',
        'floors_count' => '25',
        'building_type' => 'brick',
        'eco_rating' => '5',
        'rooms' => array(
            array('room_area' => 135, 'rooms_count' => '4', 'balcony' => 'yes', 'bathroom' => 'yes'),
            array('room_area' => 180, 'rooms_count' => '5', 'balcony' => 'yes', 'bathroom' => 'yes'),
        )
    ),
    array(
        'title' => 'Сімейний дім',
        'content' => 'Затишний будинок для великих родин',
        'district' => 'Шевченківський',
        'building_name' => 'Родинний затишок',
        'coordinates' => '50.4581, 30.5147',
        'floors_count' => '5',
        'building_type' => 'brick',
        'eco_rating' => '4',
        'rooms' => array(
            array('room_area' => 95, 'rooms_count' => '3', 'balcony' => 'yes', 'bathroom' => 'yes'),
            array('room_area' => 110, 'rooms_count' => '4', 'balcony' => 'yes', 'bathroom' => 'yes'),
        )
    )
);

// Create posts
foreach ($buildings as $building) {
    $post_id = wp_insert_post(array(
        'post_title' => $building['title'],
        'post_content' => $building['content'],
        'post_type' => 'real_estate',
        'post_status' => 'publish'
    ));
    
    if ($post_id) {
        // Set district
        wp_set_post_terms($post_id, $building['district'], 'district');
        
        // Set ACF fields
        update_field('building_name', $building['building_name'], $post_id);
        update_field('coordinates', $building['coordinates'], $post_id);
        update_field('floors_count', $building['floors_count'], $post_id);
        update_field('building_type', $building['building_type'], $post_id);
        update_field('eco_rating', $building['eco_rating'], $post_id);
        update_field('rooms', $building['rooms'], $post_id);
        
        echo "Created: " . $building['title'] . " (ID: $post_id)\n";
    }
}
?>