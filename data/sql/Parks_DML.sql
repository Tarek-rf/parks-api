USE parks;

-- =======================================
-- Seed data (10 rows per table)
-- Order: core -> link -> event/policy -> transactional
-- =======================================

-- 1) location (10)
INSERT INTO locations (name, country, province, address, latitude, longitude, area_km2, max_elevation_m, continent, website) VALUES
('Maple Ridge Park','Canada','British Columbia','123 Forest Rd',49.2827,-123.1207,512.30,2401,'North America','https://mapleridge.example'),
('Aurora Valley','Canada','Alberta','77 Valley Way',51.0486,-114.0708,780.00,3205,'North America','https://auroravalley.example'),
('Gaspé Shore','Canada','Quebec','9 Côte du Parc',48.8367,-64.4819,345.25,1102,'North America','https://gaspe.example'),
('Prairie Winds','Canada','Saskatchewan','42 Prairie Dr',50.4452,-104.6189,910.50,842,'North America','https://prairiewinds.example'),
('Northern Lights','Canada','Manitoba','5 Aurora St',49.8951,-97.1384,620.00,1450,'North America','https://northernlights.example'),
('Bayview Dunes','Canada','Nova Scotia','88 Sand Dune Rd',44.6488,-63.5752,210.40,305,'North America','https://bayviewdunes.example'),
('Rocky Summit','Canada','Alberta','1 Summit Trail',51.1784,-115.5708,1022.11,3456,'North America','https://rockysummit.example'),
('Emerald Lake','Canada','British Columbia','12 Lake Loop',51.4420,-116.5320,189.75,2100,'North America','https://emeraldlake.example'),
('Red Cliff','Canada','Ontario','700 Cliffside Ave',48.3809,-89.2477,455.60,980,'North America','https://redcliff.example'),
('Seaside Point','Canada','Newfoundland and Labrador','3 Lighthouse Rd',47.5615,-52.7126,155.90,420,'North America','https://seasidepoint.example');

-- 2) animal_species (10)
INSERT INTO animals (common_name, scientific_name, class_name, family_name, population, conservation_status, average_weight_kg, average_height_cm, diet, phylum) VALUES
('Gray Wolf','Canis lupus','Mammalia','Canidae',1200,'least_concern',45.0,80.0,'carnivore','Chordata'),
('Moose','Alces alces','Mammalia','Cervidae',850,'least_concern',380.0,190.0,'herbivore','Chordata'),
('Bald Eagle','Haliaeetus leucocephalus','Aves','Accipitridae',320,'least_concern',6.5,85.0,'carnivore','Chordata'),
('Grizzly Bear','Ursus arctos horribilis','Mammalia','Ursidae',260,'threatened',270.0,200.0,'omnivore','Chordata'),
('Beaver','Castor canadensis','Mammalia','Castoridae',3000,'least_concern',25.0,30.0,'herbivore','Chordata'),
('Snowshoe Hare','Lepus americanus','Mammalia','Leporidae',540,'least_concern',1.6,40.0,'herbivore','Chordata'),
('Lynx','Lynx canadensis','Mammalia','Felidae',190,'threatened',8.0,55.0,'carnivore','Chordata'),
('Trumpeter Swan','Cygnus buccinator','Aves','Anatidae',110,'threatened',12.0,120.0,'herbivore','Chordata'),
('Red Fox','Vulpes vulpes','Mammalia','Canidae',950,'least_concern',7.0,40.0,'omnivore','Chordata'),
('Wolverine','Gulo gulo','Mammalia','Mustelidae',70,'threatened',15.0,35.0,'carnivore','Chordata');

-- 3) activity (10)
INSERT INTO activities (activity_name, activity_type, minimum_age, minimum_height_cm, risk_level, duration_minutes, max_group_size, guide_required, permit_required, cost_per_person) VALUES
('Canoe Tour','water',12,NULL,2,120,8,1,0,45.00),
('Kayaking','water',14,NULL,3,90,6,1,0,55.00),
('River Rafting','water',16,NULL,5,150,8,1,1,95.00),
('Snowshoe Hike','snow',10,NULL,2,120,12,0,0,20.00),
('Cross-Country Ski','snow',12,NULL,3,120,10,0,0,25.00),
('Glacier Trek','land',16,NULL,4,240,6,1,1,120.00),
('Wildlife Walk','land',8,NULL,1,60,15,0,0,10.00),
('Mountain Hike','land',12,NULL,3,180,12,0,0,0.00),
('Rock Climb','land',16,150,5,180,6,1,1,150.00),
('Bird Watching','land',6,NULL,1,90,20,0,0,0.00);

-- 4) body_water (10)
INSERT INTO waters (name, type, max_depth_m, is_fresh_water, flow, surface_area_km2, water_temperature_c, water_quality_index, protected_status) VALUES
('Silver Lake','lake',35.5,1,NULL,2.100,15.2,82.5,'protected'),
('Crystal River','river',12.0,1,'turbulent',1.850,10.3,78.0,'protected'),
('Hidden Pond','pond',5.2,1,NULL,0.120,18.1,80.0,'none'),
('Eagle Creek','creek',3.4,1,'laminar',0.300,12.0,76.0,'none'),
('Summit River','river',20.0,1,'transitional',3.750,9.5,74.0,'protected'),
('Aspen Lake','lake',28.0,1,NULL,1.950,16.0,83.0,'protected'),
('Bear Brook','brook',2.5,1,'laminar',0.090,11.2,72.0,'none'),
('Misty Bay','bay',40.0,0,NULL,6.500,7.0,69.0,'none'),
('Otter Stream','stream',1.8,1,'turbulent',0.060,13.3,75.0,'none'),
('Glacier Lagoon','lagoon',55.0,0,NULL,4.400,4.5,65.0,'protected');

-- 5) vegetation (10)
INSERT INTO vegetations (species_name, type, scientific_name, climate_pref, conservation_status, avg_canopy_diameter_m, growth_rate, bloom_season, soil_preference, is_invasive) VALUES
('White Spruce','tree','Picea glauca','boreal','secure',6.0,'moderate',NULL,'loamy',0),
('Lodgepole Pine','tree','Pinus contorta','subalpine','secure',7.5,'fast',NULL,'sandy',0),
('Paper Birch','tree','Betula papyrifera','temperate','secure',5.0,'moderate',NULL,'loamy',0),
('Fireweed','flower','Chamerion angustifolium','boreal','secure',0.8,'fast','summer','loamy',0),
('Arctic Lupine','flower','Lupinus arcticus','subarctic','sensitive',0.6,'moderate','summer','sandy',0),
('Bunchberry','shrub','Cornus canadensis','boreal','secure',0.4,'slow','spring','peaty',0),
('Common Reed','grass','Phragmites australis','temperate','invasive',1.2,'fast','summer','clay',1),
('Black Spruce','tree','Picea mariana','boreal','secure',5.5,'slow',NULL,'peaty',0),
('Mountain Heather','shrub','Phyllodoce empetriformis','alpine','secure',0.5,'slow','summer','sandy',0),
('Wild Rose','shrub','Rosa acicularis','temperate','secure',1.2,'moderate','spring','loamy',0);

-- 6) visitor (10)
INSERT INTO visitors (first_name, last_name, age_group, gender, height_cm, country_of_origin, is_citizen, address, email, phone_number) VALUES
('Amira','Khan','adult','female',165,'Canada',1,'101 Pine St','amira.khan@example.com','+1-555-0001'),
('Liam','Ross','teen','male',172,'Canada',1,'22 River Rd','liam.ross@example.com','+1-555-0002'),
('Noah','Baker','adult','male',181,'USA',0,'9 Summit Ave','noah.baker@example.com','+1-555-0003'),
('Emma','Martin','adult','female',168,'France',0,'77 Lake View','emma.martin@example.com','+1-555-0004'),
('Sophia','Nguyen','child','female',135,'Canada',1,'14 Birch Ln','sophia.nguyen@example.com','+1-555-0005'),
('Ava','Chen','adult','female',158,'China',0,'88 Maple Rd','ava.chen@example.com','+1-555-0006'),
('Ethan','Patel','senior','male',174,'India',0,'33 Glacier Ct','ethan.patel@example.com','+1-555-0007'),
('Mia','Lopez','adult','female',162,'Mexico',0,'5 Bay St','mia.lopez@example.com','+1-555-0008'),
('Lucas','Smith','adult','male',179,'Canada',1,'19 Prairie Dr','lucas.smith@example.com','+1-555-0009'),
('Isabella','Rossi','teen','female',160,'Italy',0,'4 Cliffside Rd','isabella.rossi@example.com','+1-555-0010');

-- 7) location_animal (10)  -- map first 10 combos
INSERT INTO location_animal (location_id, animal_id) VALUES
(1,1),(1,5),
(2,4),
(3,3),
(4,2),
(5,8),
(6,9),
(7,4),
(8,7),
(9,10);

-- 8) location_activity (10)
INSERT INTO location_activity (location_id, activity_id) VALUES
(1,1),(1,7),
(2,6),
(3,4),
(4,8),
(5,2),
(6,10),
(7,9),
(8,5),
(10,3);

-- 9) location_water (10)
INSERT INTO location_water (location_id, water_id) VALUES
(1,1),
(2,2),
(3,3),
(4,4),
(5,5),
(6,6),
(7,7),
(8,8),
(9,9),
(10,10);

-- 10) location_vegetation (10)
INSERT INTO location_vegetation (location_id, vegetation_id) VALUES
(1,1),
(2,2),
(3,3),
(4,4),
(5,5),
(6,6),
(7,7),
(8,8),
(9,9),
(10,10);

-- 11) history (10)
INSERT INTO history (location_id, founder, founded_date, most_interesting_fact, major_historic_accident, age_of_park, was_native_land, preservation_laws_enacted, significant_restoration_year) VALUES
(1,'J. Douglas','1965-06-12','Old-growth spruce stands','1969 landslide near west ridge',59,1,'Forest Act 1971; Wildlife Act 1975',1998),
(2,'H. Sinclair','1958-07-01','Glacier-fed valley visible from main trail','1972 avalanche at north slope',67,1,'Park Preservation Code 1960',1995),
(3,'M. Tremblay','1978-09-18','Migratory seabirds nesting cliffs','1986 storm surge damage',47,0,'Coastal Safety Mandate 1980',2001),
(4,'E. Carver','1940-05-20','Largest prairie dog colony in region','1955 wild grass fire',85,0,'Prairie Conservation Law 1946',1990),
(5,'L. Green','1985-10-05','Aurora viewing deck','1991 winter blizzard closure',40,1,'Northern Lights Reserve Act 1987',2005),
(6,'S. Doyle','1972-04-14','Shifting dune fields','1979 dune collapse',53,0,'Coastal Dunes Protection 1975',1999),
(7,'R. Abbott','1930-08-10','Triassic fossils near summit','1938 rockfall event',95,1,'Mountain Heritage Act 1932',2002),
(8,'C. Young','1976-06-22','Emerald-green glacial lake','1980 ice calving wave',49,0,'Alpine Waters Safeguard 1978',2010),
(9,'T. White','1982-03-09','Red iron cliffs with lichens','1988 cliff erosion',43,0,'Cliffside Protection Statute 1984',2008),
(10,'N. Fisher','1990-11-30','Historic lighthouse at point','1995 storm destruction of pier',34,1,'Maritime Heritage Law 1991',2015);

-- 12) policy (10)
INSERT INTO policies (location_id, policy_name, license_details, policy_description, category, effective_date, expiry_date, penalty_details, enforcement_agency, is_active) VALUES
(1,'Backcountry Permit',NULL,'Permit required for overnight camping','access','2020-01-01',NULL,'$250 fine for violations','Park Rangers',1),
(2,'Glacier Access','Crampon cert required','Guided access only','safety','2021-06-01',NULL,'Immediate escort out + $500 fine','Mountain Safety Unit',1),
(3,'Coastal Wildlife Buffer',NULL,'50m buffer from nests','conservation','2019-03-01',NULL,'$300 per incident','Wildlife Service',1),
(4,'Fire Ban',NULL,'No open flames during dry season','safety','2022-07-01','2022-10-01','$400 per violation','Fire Authority',0),
(5,'Aurora Deck Hours',NULL,'Viewing deck closes at 01:00','access','2020-09-01',NULL,'$150 after-hours','Park Rangers',1),
(6,'Dune Restoration Zone',NULL,'No walking on fenced dunes','conservation','2018-05-15',NULL,'$200 per incident','Coastal Patrol',1),
(7,'Climbing Permit','Lead cert L1','Permit required for multi-pitch','fees','2023-04-01',NULL,'$60 processing fee','Climb Office',1),
(8,'Boat Speed Limit',NULL,'Max 10 km/h on lake','safety','2021-05-01',NULL,'$250 fine','Marine Unit',1),
(9,'Drone Ban',NULL,'No recreational drones','safety','2022-01-01',NULL,'$500 confiscation','Park Rangers',1),
(10,'Lighthouse Area Fees',NULL,'Entry fee supports restoration','fees','2020-06-01',NULL,'$25 per adult','Heritage Office',1);

-- 13) natural_disaster (10)
INSERT INTO disasters (location_id, disaster_type, impact_details, event_date, severity, affected_area_sq_km, fatalities, injuries_count, evacuations, recovery_duration_days, estimated_damage_musd) VALUES
(1,'Landslide','Trail buried near west ridge','1969-08-17','moderate',1.25,0,3,20,60,0.80),
(2,'Avalanche','Snow slide on north slope','1972-02-09','severe',2.60,2,5,80,120,3.20),
(3,'Storm Surge','Cliff erosion and nest loss','1986-11-04','moderate',0.90,0,1,30,45,0.50),
(4,'Wildfire','Grass fire across prairie','1955-07-22','severe',15.00,1,12,200,180,5.50),
(5,'Blizzard','Road closures, hypothermia cases','1991-01-14','moderate',4.00,0,4,60,30,0.70),
(6,'Dune Collapse','Boardwalk damage','1979-09-01','minor',0.20,0,0,0,25,0.10),
(7,'Rockfall','Climber route closed','1938-06-03','moderate',0.50,0,2,10,40,0.30),
(8,'Ice Calving Wave','Shoreline flooded','1980-08-29','minor',0.35,0,1,5,20,0.15),
(9,'Cliff Erosion','Trail reroute required','1988-05-18','minor',0.60,0,0,0,15,0.08),
(10,'Hurricane Remnants','Pier destroyed','1995-10-07','severe',2.10,0,6,120,150,2.80);

-- 14) visit (10)
INSERT INTO visits (visitor_id, location_id, activity_id, days_spent, visit_date, visit_type, total_cost, payment_method, feedback_rating) VALUES
(1,1,7,1,'2025-06-10','day',10.00,'card',5),
(2,2,6,2,'2025-02-14','overnight',240.00,'card',4),
(3,3,4,1,'2025-07-21','day',20.00,'cash',5),
(4,4,8,3,'2024-08-03','camp',0.00,'online',4),
(5,5,2,1,'2025-03-30','day',55.00,'card',5),
(6,6,10,1,'2025-05-12','day',0.00,'cash',4),
(7,7,9,2,'2023-09-05','overnight',300.00,'card',5),
(8,8,5,1,'2024-06-18','day',25.00,'card',4),
(9,9,1,2,'2025-01-09','overnight',90.00,'online',3),
(10,10,3,2,'2024-09-27','overnight',190.00,'card',5);

-- 15) injury (10)
INSERT INTO injuries (visitor_id, location_id, injury_name, injury_type, recovery_time_days, body_part_affected, severity_level, treatment_provided, treatment_on_site, hospital_referred, incident_description, reported_by) VALUES
(1,1,'Sprained Ankle','sprain',14,'ankle','moderate','compression + rest',1,0,'Twisted on uneven root','ranger'),
(2,2,'Frostnip','cold',3,'fingers','minor','warming packs',1,0,'Cold exposure during trek','guide'),
(3,3,'Bee Sting','allergic',2,'arm','minor','antihistamine',1,0,'Stung near meadow','self'),
(4,4,'Sunburn','burn',5,'back','minor','aloe + hydration',1,0,'Forgot sunscreen on hike','medic'),
(5,5,'Hypothermia Mild','cold',7,'systemic','moderate','warming shelter',1,0,'Wind chill at deck','ranger'),
(6,6,'Scrape','abrasion',4,'knee','minor','clean + bandage',1,0,'Fell off boardwalk step','self'),
(7,7,'Rockfall Bruise','contusion',10,'shoulder','moderate','ice + rest',1,0,'Loose rocks on trail','guide'),
(8,8,'Blister','friction',3,'heel','minor','moleskin',1,0,'Boots rubbed on steep path','self'),
(9,9,'Dehydration','illness',2,'systemic','moderate','oral rehydration',1,0,'Heat on cliff trail','medic'),
(10,10,'Rope Burn','abrasion',6,'hand','minor','clean + ointment',1,0,'Belay mishap','guide');

-- =======================================
-- Additional seed data (5 rows per table)
-- Order: core -> link -> event/policy -> transactional
-- =======================================

-- 1) locations (5 additional)
INSERT INTO locations (name, country, province, address, latitude, longitude, area_km2, max_elevation_m, continent, website) VALUES
('Starlight Canyon', 'Canada', 'British Columbia', '45 Canyon Trail', 50.1234, -122.9876, 675.80, 2800, 'North America', 'https://starlightcanyon.example'),
('Frosty Peaks', 'Canada', 'Alberta', '99 Snowy Rd', 52.3456, -115.2345, 890.20, 3600, 'North America', 'https://frostypeaks.example'),
('Harbor Mist', 'Canada', 'Nova Scotia', '12 Coastal Way', 45.6789, -62.3456, 180.50, 250, 'North America', 'https://harbormist.example'),
('Cedar Grove', 'Canada', 'Ontario', '33 Woodland Dr', 47.8901, -88.1234, 420.75, 1100, 'North America', 'https://cedargrove.example'),
('Twilight Marsh', 'Canada', 'Manitoba', '8 Marshland Ave', 50.5678, -96.7890, 310.30, 600, 'North America', 'https://twilightmarsh.example');

-- 2) animals (5 additional)
INSERT INTO animals (common_name, scientific_name, class_name, family_name, population, conservation_status, average_weight_kg, average_height_cm, diet, phylum) VALUES
('River Otter', 'Lontra canadensis', 'Mammalia', 'Mustelidae', 600, 'least_concern', 10.5, 60.0, 'carnivore', 'Chordata'),
('Peregrine Falcon', 'Falco peregrinus', 'Aves', 'Falconidae', 150, 'least_concern', 1.0, 45.0, 'carnivore', 'Chordata'),
('Porcupine', 'Erethizon dorsatum', 'Mammalia', 'Erethizontidae', 800, 'least_concern', 12.0, 50.0, 'herbivore', 'Chordata'),
('Mountain Goat', 'Oreamnos americanus', 'Mammalia', 'Bovidae', 200, 'least_concern', 90.0, 120.0, 'herbivore', 'Chordata'),
('Great Horned Owl', 'Bubo virginianus', 'Aves', 'Strigidae', 90, 'least_concern', 1.5, 55.0, 'carnivore', 'Chordata');

-- 3) activities (5 additional)
INSERT INTO activities (activity_name, activity_type, minimum_age, minimum_height_cm, risk_level, duration_minutes, max_group_size, guide_required, permit_required, cost_per_person) VALUES
('Nature Photography', 'land', 10, NULL, 1, 120, 15, 0, 0, 30.00),
('Ice Climbing', 'snow', 18, 160, 5, 240, 4, 1, 1, 200.00),
('Paddleboarding', 'water', 14, NULL, 3, 90, 8, 0, 0, 40.00),
('Stargazing Tour', 'land', 8, NULL, 1, 120, 20, 1, 0, 25.00),
('Backcountry Camping', 'land', 16, NULL, 4, 1440, 10, 0, 1, 50.00);

-- 4) waters (5 additional)
INSERT INTO waters (name, type, max_depth_m, is_fresh_water, flow, surface_area_km2, water_temperature_c, water_quality_index, protected_status) VALUES
('Moonlit Lake', 'lake', 42.0, 1, NULL, 3.200, 14.5, 81.0, 'protected'),
('Swift Creek', 'creek', 2.8, 1, 'turbulent', 0.250, 11.0, 77.0, 'none'),
('Coral Bay', 'bay', 30.5, 0, NULL, 5.100, 8.0, 70.0, 'protected'),
('Pine River', 'river', 15.5, 1, 'laminar', 2.300, 12.5, 79.0, 'protected'),
('Tranquil Pond', 'pond', 4.0, 1, NULL, 0.150, 17.0, 82.0, 'none');

-- 5) vegetations (5 additional)
INSERT INTO vegetations (species_name, type, scientific_name, climate_pref, conservation_status, avg_canopy_diameter_m, growth_rate, bloom_season, soil_preference, is_invasive) VALUES
('Trembling Aspen', 'tree', 'Populus tremuloides', 'temperate', 'secure', 6.5, 'fast', NULL, 'loamy', 0),
('Blue Columbine', 'flower', 'Aquilegia coerulea', 'alpine', 'secure', 0.5, 'moderate', 'summer', 'sandy', 0),
('Bog Laurel', 'shrub', 'Kalmia polifolia', 'boreal', 'secure', 0.7, 'slow', 'spring', 'peaty', 0),
('Foxtail Grass', 'grass', 'Alopecurus pratensis', 'temperate', 'secure', 0.3, 'fast', 'summer', 'clay', 0),
('Alpine Willow', 'shrub', 'Salix arctica', 'subarctic', 'sensitive', 0.4, 'slow', 'summer', 'sandy', 0);

-- 6) visitors (5 additional)
INSERT INTO visitors (first_name, last_name, age_group, gender, height_cm, country_of_origin, is_citizen, address, email, phone_number) VALUES
('Jacob', 'Wong', 'adult', 'male', 175, 'Canada', 1, '56 Cedar St', 'jacob.wong@example.com', '+1-555-0011'),
('Olivia', 'Brown', 'senior', 'female', 160, 'USA', 0, '23 Ocean Dr', 'olivia.brown@example.com', '+1-555-0012'),
('Elijah', 'Garcia', 'teen', 'male', 170, 'Mexico', 0, '11 Hilltop Rd', 'elijah.garcia@example.com', '+1-555-0013'),
('Charlotte', 'Lee', 'adult', 'female', 163, 'Canada', 1, '78 Spruce Ave', 'charlotte.lee@example.com', '+1-555-0014'),
('James', 'Taylor', 'child', 'male', 140, 'UK', 0, '44 Forest Ln', 'james.taylor@example.com', '+1-555-0015');

-- 7) location_animal (5 additional)
INSERT INTO location_animal (location_id, animal_id) VALUES
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15);

-- 8) location_activity (5 additional)
INSERT INTO location_activity (location_id, activity_id) VALUES
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15);

-- 9) location_water (5 additional)
INSERT INTO location_water (location_id, water_id) VALUES
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15);

-- 10) location_vegetation (5 additional)
INSERT INTO location_vegetation (location_id, vegetation_id) VALUES
(11, 11),
(12, 12),
(13, 13),
(14, 14),
(15, 15);

-- 11) history (5 additional)
INSERT INTO history (location_id, founder, founded_date, most_interesting_fact, major_historic_accident, age_of_park, was_native_land, preservation_laws_enacted, significant_restoration_year) VALUES
(11, 'A. Harper', '1968-04-25', 'Canyon glows under starlight', '1975 rockslide', 57, 1, 'Canyon Protection Act 1970', 2000),
(12, 'B. Frost', '1955-11-10', 'Highest peak in region', '1962 avalanche', 70, 0, 'Alpine Preservation Code 1958', 1997),
(13, 'C. Malone', '1980-07-15', 'Rare coastal fog ecosystem', '1989 storm flooding', 45, 0, 'Harbor Conservation Law 1982', 2003),
(14, 'D. Ellis', '1970-03-20', 'Oldest cedar forest', '1978 forest fire', 55, 1, 'Forest Heritage Act 1972', 2006),
(15, 'E. Ward', '1992-09-05', 'Migratory bird sanctuary', '1999 marsh flood', 33, 1, 'Wetland Protection Act 1994', 2012);

-- 12) policies (5 additional)
INSERT INTO policies (location_id, policy_name, license_details, policy_description, category, effective_date, expiry_date, penalty_details, enforcement_agency, is_active) VALUES
(11, 'Canyon Trail Permit', NULL, 'Permit for overnight hikes', 'access', '2021-03-01', NULL, '$200 fine', 'Park Rangers', 1),
(12, 'Ice Safety Gear', 'Ice axe cert', 'Mandatory gear for ice routes', 'safety', '2022-12-01', NULL, '$300 fine', 'Mountain Safety Unit', 1),
(13, 'Bird Sanctuary Zone', NULL, 'No access during nesting', 'conservation', '2020-04-01', NULL, '$350 fine', 'Wildlife Service', 1),
(14, 'Firewood Ban', NULL, 'No external firewood allowed', 'safety', '2023-06-01', NULL, '$150 fine', 'Fire Authority', 1),
(15, 'Marsh Access Limit', NULL, 'Restricted boardwalk hours', 'access', '2021-09-01', NULL, '$100 fine', 'Park Rangers', 1);

-- 13) disasters (5 additional)
INSERT INTO disasters (location_id, disaster_type, impact_details, event_date, severity, affected_area_sq_km, fatalities, injuries_count, evacuations, recovery_duration_days, estimated_damage_musd) VALUES
(11, 'Rockslide', 'Trail closure near canyon', '1975-05-12', 'moderate', 0.80, 0, 2, 15, 50, 0.60),
(12, 'Avalanche', 'Snow covered main path', '1962-03-08', 'severe', 3.10, 1, 4, 90, 140, 4.00),
(13, 'Flood', 'Boardwalk damage', '1989-10-20', 'minor', 0.40, 0, 0, 0, 30, 0.20),
(14, 'Forest Fire', 'Cedar grove partial loss', '1978-08-15', 'severe', 10.00, 0, 8, 150, 200, 6.50),
(15, 'Flood', 'Marsh trail submerged', '1999-06-22', 'moderate', 1.50, 0, 1, 20, 60, 0.90);

-- 14) visits (5 additional)
INSERT INTO visits (visitor_id, location_id, activity_id, days_spent, visit_date, visit_type, total_cost, payment_method, feedback_rating) VALUES
(11, 11, 11, 1, '2025-07-15', 'day', 30.00, 'card', 5),
(12, 12, 12, 2, '2025-01-20', 'overnight', 400.00, 'online', 4),
(13, 13, 13, 1, '2025-08-10', 'day', 40.00, 'cash', 5),
(14, 14, 14, 1, '2025-06-05', 'day', 25.00, 'card', 4),
(15, 15, 15, 3, '2024-10-12', 'camp', 150.00, 'online', 5);

-- 15) injuries (5 additional)
INSERT INTO injuries (visitor_id, location_id, injury_name, injury_type, recovery_time_days, body_part_affected, severity_level, treatment_provided, treatment_on_site, hospital_referred, incident_description, reported_by) VALUES
(11, 11, 'Wrist Sprain', 'sprain', 10, 'wrist', 'moderate', 'brace + rest', 1, 0, 'Slipped on canyon trail', 'ranger'),
(12, 12, 'Frostbite', 'cold', 14, 'toes', 'moderate', 'warming + medical', 1, 1, 'Ice climb exposure', 'guide'),
(13, 13, 'Cut', 'laceration', 5, 'leg', 'minor', 'clean + stitches', 1, 0, 'Boardwalk splinter', 'self'),
(14, 14, 'Heat Exhaustion', 'illness', 3, 'systemic', 'moderate', 'hydration + rest', 1, 0, 'Hot day in grove', 'medic'),
(15, 15, 'Ankle Twist', 'sprain', 7, 'ankle', 'minor', 'ice + wrap', 1, 0, 'Tripped on marsh boardwalk', 'ranger');

