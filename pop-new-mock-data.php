#!/bin/bash

# Real Estate Mock Data Generator for WP-CLI
# Run this script in your WordPress root directory

echo "Creating Real Estate Mock Data..."

# Create districts first
echo "Creating districts..."
wp term create district "Печерський" --description="Центральний район Києва" --allow-root
wp term create district "Шевченківський" --description="Історичний центр міста" --allow-root
wp term create district "Подільський" --description="Район на Подолі" --allow-root
wp term create district "Оболонський" --description="Житловий район на Оболоні" --allow-root
wp term create district "Солом'янський" --description="Промисловий район" --allow-root

echo "Creating Real Estate Objects..."

# Object 1 - Luxury Complex
wp post create --allow-root \
  --post_type=real_estate \
  --post_title="ЖК Maple Park" \
  --post_content="Преміальний житловий комплекс у серці Печерського району. Сучасна архітектура та найкращі матеріали." \
  --post_status=publish \
  --meta_input='{
    "building_name": "ЖК Maple Park",
    "coordinates": "50.4260, 30.5383",
    "floors_count": "15",
    "building_type": "brick",
    "eco_rating": "5",
    "apart_1_enabled": "1",
    "apart_1_area": "85.5",
    "apart_1_rooms_count": "3",
    "apart_1_balcony": "yes",
    "apart_1_bathroom": "yes",
    "apart_2_enabled": "1",
    "apart_2_area": "120.8",
    "apart_2_rooms_count": "4",
    "apart_2_balcony": "yes",
    "apart_2_bathroom": "yes",
    "apart_3_enabled": "1",
    "apart_3_area": "65.2",
    "apart_3_rooms_count": "2",
    "apart_3_balcony": "no",
    "apart_3_bathroom": "yes"
  }' \
  --tax_input='{"district":["Печерський"]}'

# Object 2 - Budget Building
wp post create  --allow-root \
  --post_type=real_estate \
  --post_title="Будинок на вул. Січових Стрільців" \
  --post_content="Бюджетний житловий будинок з хорошим транспортним сполученням." \
  --post_status=publish \
  --meta_input='{
    "building_name": "Будинок Січових Стрільців 12",
    "coordinates": "50.4501, 30.5234",
    "floors_count": "9",
    "building_type": "panel",
    "eco_rating": "3",
    "apart_1_enabled": "1",
    "apart_1_area": "45.0",
    "apart_1_rooms_count": "1",
    "apart_1_balcony": "yes",
    "apart_1_bathroom": "yes",
    "apart_2_enabled": "1",
    "apart_2_area": "58.3",
    "apart_2_rooms_count": "2",
    "apart_2_balcony": "yes",
    "apart_2_bathroom": "yes"
  }' \
  --tax_input='{"district":["Шевченківський"]}'

# Object 3 - Modern Tower
wp post create  --allow-root \
  --post_type=real_estate \
  --post_title="Sky Tower Residential" \
  --post_content="Сучасна висотка з панорамними вікнами та розвиненою інфраструктурою." \
  --post_status=publish \
  --meta_input='{
    "building_name": "Sky Tower",
    "coordinates": "50.4648, 30.5234",
    "floors_count": "20",
    "building_type": "foam_block",
    "eco_rating": "4",
    "apart_1_enabled": "1",
    "apart_1_area": "95.7",
    "apart_1_rooms_count": "3",
    "apart_1_balcony": "yes",
    "apart_1_bathroom": "yes",
    "apart_2_enabled": "1",
    "apart_2_area": "135.2",
    "apart_2_rooms_count": "4",
    "apart_2_balcony": "yes",
    "apart_2_bathroom": "yes",
    "apart_3_enabled": "1",
    "apart_3_area": "78.5",
    "apart_3_rooms_count": "2",
    "apart_3_balcony": "yes",
    "apart_3_bathroom": "yes",
    "apart_4_enabled": "1",
    "apart_4_area": "180.0",
    "apart_4_rooms_count": "5",
    "apart_4_balcony": "yes",
    "apart_4_bathroom": "yes"
  }' \
  --tax_input='{"district":["Оболонський"]}'

# Object 4 - Eco House
wp post create  --allow-root \
  --post_type=real_estate \
  --post_title="Green Valley Residence" \
  --post_content="Екологічний житловий комплекс з використанням природних матеріалів." \
  --post_status=publish \
  --meta_input='{
    "building_name": "Green Valley",
    "coordinates": "50.4112, 30.5167",
    "floors_count": "6",
    "building_type": "brick",
    "eco_rating": "5",
    "apart_1_enabled": "1",
    "apart_1_area": "72.8",
    "apart_1_rooms_count": "2",
    "apart_1_balcony": "yes",
    "apart_1_bathroom": "yes",
    "apart_2_enabled": "1",
    "apart_2_area": "105.4",
    "apart_2_rooms_count": "3",
    "apart_2_balcony": "yes",
    "apart_2_bathroom": "yes"
  }' \
  --tax_input='{"district":["Подільський"]}'

# Object 5 - Student Housing
wp post create --allow-root \
  --post_type=real_estate \
  --post_title="Студентський Гуртожиток КПІ" \
  --post_content="Сучасний студентський гуртожиток поблизу КПІ з усіма зручностями." \
  --post_status=publish \
  --meta_input='{
    "building_name": "Гуртожиток КПІ №15",
    "coordinates": "50.4490, 30.4560",
    "floors_count": "12",
    "building_type": "panel",
    "eco_rating": "2",
    "apart_1_enabled": "1",
    "apart_1_area": "24.0",
    "apart_1_rooms_count": "1",
    "apart_1_balcony": "no",
    "apart_1_bathroom": "yes",
    "apart_2_enabled": "1",
    "apart_2_area": "24.0",
    "apart_2_rooms_count": "1",
    "apart_2_balcony": "no",
    "apart_2_bathroom": "yes",
    "apart_3_enabled": "1",
    "apart_3_area": "32.5",
    "apart_3_rooms_count": "1",
    "apart_3_balcony": "yes",
    "apart_3_bathroom": "yes"
  }' \
  --tax_input='{"district":["Солом'\''янський"]}'

# Object 6 - Historic Building
wp post create --allow-root \
  --post_type=real_estate \
  --post_title="Історичний Особняк на Андріївському узвозі" \
  --post_content="Відреставрований історичний будинок з автентичними деталями та сучасними зручностями." \
  --post_status=publish \
  --meta_input='{
    "building_name": "Особняк Андріївський 25",
    "coordinates": "50.4592, 30.5169",
    "floors_count": "3",
    "building_type": "brick",
    "eco_rating": "4",
    "apart_1_enabled": "1",
    "apart_1_area": "156.7",
    "apart_1_rooms_count": "5",
    "apart_1_balcony": "yes",
    "apart_1_bathroom": "yes",
    "apart_2_enabled": "1",
    "apart_2_area": "89.2",
    "apart_2_rooms_count": "3",
    "apart_2_balcony": "no",
    "apart_2_bathroom": "yes"
  }' \
  --tax_input='{"district":["Подільський"]}'

# Object 7 - New Development
wp post create --allow-root \
  --post_type=real_estate \
  --post_title="ЖК Riverside Park" \
  --post_content="Новий житловий комплекс біля Дніпра з власною набережною та парковою зоною." \
  --post_status=publish \
  --meta_input='{
    "building_name": "ЖК Riverside Park",
    "coordinates": "50.4755, 30.5326",
    "floors_count": "18",
    "building_type": "foam_block",
    "eco_rating": "4",
    "apart_1_enabled": "1",
    "apart_1_area": "68.9",
    "apart_1_rooms_count": "2",
    "apart_1_balcony": "yes",
    "apart_1_bathroom": "yes",
    "apart_2_enabled": "1",
    "apart_2_area": "92.1",
    "apart_2_rooms_count": "3",
    "apart_2_balcony": "yes",
    "apart_2_bathroom": "yes",
    "apart_3_enabled": "1",
    "apart_3_area": "115.6",
    "apart_3_rooms_count": "3",
    "apart_3_balcony": "yes",
    "apart_3_bathroom": "yes",
    "apart_4_enabled": "1",
    "apart_4_area": "145.0",
    "apart_4_rooms_count": "4",
    "apart_4_balcony": "yes",
    "apart_4_bathroom": "yes",
    "apart_5_enabled": "1",
    "apart_5_area": "55.3",
    "apart_5_rooms_count": "1",
    "apart_5_balcony": "yes",
    "apart_5_bathroom": "yes"
  }' \
  --tax_input='{"district":["Оболонський"]}'

# Object 8 - Compact Living
wp post create --allow-root \
  --post_type=real_estate \
  --post_title="Smart Living Complex" \
  --post_content="Компактні розумні квартири для молодих професіоналів з високотехнологічними рішеннями." \
  --post_status=publish \
  --meta_input='{
    "building_name": "Smart Living",
    "coordinates": "50.4333, 30.5167",
    "floors_count": "14",
    "building_type": "foam_block",
    "eco_rating": "3",
    "apart_1_enabled": "1",
    "apart_1_area": "35.8",
    "apart_1_rooms_count": "1",
    "apart_1_balcony": "no",
    "apart_1_bathroom": "yes",
    "apart_2_enabled": "1",
    "apart_2_area": "42.5",
    "apart_2_rooms_count": "1",
    "apart_2_balcony": "yes",
    "apart_2_bathroom": "yes",
    "apart_3_enabled": "1",
    "apart_3_area": "58.7",
    "apart_3_rooms_count": "2",
    "apart_3_balcony": "yes",
    "apart_3_bathroom": "yes"
  }' \
  --tax_input='{"district":["Печерський"]}'

# Object 9 - Family Complex
wp post create --allow-root \
  --post_type=real_estate \
  --post_title="Family Park Residence" \
  --post_content="Сімейний житловий комплекс з дитячими майданчиками, школою та медичним центром." \
  --post_status=publish \
  --meta_input='{
    "building_name": "Family Park",
    "coordinates": "50.4089, 30.6234",
    "floors_count": "10",
    "building_type": "brick",
    "eco_rating": "4",
    "apart_1_enabled": "1",
    "apart_1_area": "98.4",
    "apart_1_rooms_count": "3",
    "apart_1_balcony": "yes",
    "apart_1_bathroom": "yes",
    "apart_2_enabled": "1",
    "apart_2_area": "125.7",
    "apart_2_rooms_count": "4",
    "apart_2_balcony": "yes",
    "apart_2_bathroom": "yes",
    "apart_3_enabled": "1",
    "apart_3_area": "145.9",
    "apart_3_rooms_count": "4",
    "apart_3_balcony": "yes",
    "apart_3_bathroom": "yes",
    "apart_4_enabled": "1",
    "apart_4_area": "78.2",
    "apart_4_rooms_count": "2",
    "apart_4_balcony": "yes",
    "apart_4_bathroom": "yes",
    "apart_5_enabled": "1",
    "apart_5_area": "165.3",
    "apart_5_rooms_count": "5",
    "apart_5_balcony": "yes",
    "apart_5_bathroom": "yes",
    "apart_6_enabled": "1",
    "apart_6_area": "89.1",
    "apart_6_rooms_count": "3",
    "apart_6_balcony": "no",
    "apart_6_bathroom": "yes"
  }' \
  --tax_input='{"district":["Шевченківський"]}'

echo "Mock data created successfully!"
echo "Created 9 real estate objects with various configurations:"
echo "1. ЖК Maple Park (Luxury, 3 apartments)"
echo "2. Будинок на вул. Січових Стрільців (Budget, 2 apartments)"
echo "3. Sky Tower Residential (Modern, 4 apartments)"
echo "4. Green Valley Residence (Eco, 2 apartments)"
echo "5. Студентський Гуртожиток КПІ (Student, 3 apartments)"
echo "6. Історичний Особняк (Historic, 2 apartments)"
echo "7. ЖК Riverside Park (New Development, 5 apartments)"
echo "8. Smart Living Complex (Compact, 3 apartments)"
echo "9. Family Park Residence (Family, 6 apartments)"
echo ""
echo "Districts created: Печерський, Шевченківський, Подільський, Оболонський, Солом'янський"