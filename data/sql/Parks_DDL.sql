-- MariaDB / MySQL mode
-- Create database and use it
CREATE DATABASE IF NOT EXISTS parks;
-- CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE parks;

-- Safety first (in case you rerun the script)
SET FOREIGN_KEY_CHECKS = 0;

-- =========================
-- Core reference tables
-- =========================

-- location (from ERD "Location")
CREATE TABLE locations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  country VARCHAR(80) NOT NULL,
  province VARCHAR(80) NULL,
  address VARCHAR(200) NULL,
  latitude DECIMAL(9,6) NULL,
  longitude DECIMAL(9,6) NULL,
  area_km2 DECIMAL(12,2) NULL,
  max_elevation_m INT NULL,
  continent VARCHAR(30) NULL,
  website VARCHAR(200) NULL
) ENGINE=InnoDB;

-- animal_species (from ERD "AnimalSpecies")
CREATE TABLE animals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  common_name VARCHAR(100) NOT NULL,
  scientific_name VARCHAR(140) NOT NULL,
  class_name VARCHAR(80) NULL,
  family_name VARCHAR(80) NULL,
  population INT NULL,
  conservation_status ENUM('extinct','threatened','least_concern') NOT NULL DEFAULT 'least_concern',
  average_weight_kg DECIMAL(8,2) NULL,
  average_height_cm DECIMAL(8,2) NULL,
  diet ENUM('carnivore','herbivore','omnivore') NOT NULL DEFAULT 'omnivore',
  phylum VARCHAR(80) NULL
) ENGINE=InnoDB;

-- activity (from ERD "Activities")
CREATE TABLE activities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  activity_name VARCHAR(120) NOT NULL,
  activity_type ENUM('water','snow','land') NOT NULL,
  minimum_age INT NULL,
  minimum_height_cm INT NULL,
  risk_level TINYINT NOT NULL CHECK (risk_level BETWEEN 1 AND 5),
  duration_minutes INT NULL,
  max_group_size INT NULL,
  guide_required TINYINT(1) NOT NULL DEFAULT 0,
  permit_required TINYINT(1) NOT NULL DEFAULT 0,
  cost_per_person DECIMAL(8,2) NULL
) ENGINE=InnoDB;

-- body_water (from ERD "BodyOfWater")
CREATE TABLE waters (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  type VARCHAR(60) NOT NULL, -- lake, river, pond, etc.
  max_depth_m DECIMAL(7,2) NULL,
  is_fresh_water TINYINT(1) NOT NULL DEFAULT 1,
  flow ENUM('laminar','turbulent','transitional') NULL,
  surface_area_km2 DECIMAL(10,3) NULL,
  water_temperature_c DECIMAL(5,2) NULL,
  water_quality_index DECIMAL(5,2) NULL,
  protected_status VARCHAR(60) NULL
) ENGINE=InnoDB;

-- vegetation (from ERD "Vegetation")
CREATE TABLE vegetations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  species_name VARCHAR(140) NOT NULL,
  type VARCHAR(80) NULL,
  scientific_name VARCHAR(140) NULL,
  climate_pref VARCHAR(100) NULL,
  conservation_status VARCHAR(80) NULL,
  avg_canopy_diameter_m DECIMAL(6,2) NULL,
  growth_rate ENUM('slow','moderate','fast') NULL,
  bloom_season ENUM('spring','summer','fall','winter') NULL,
  soil_preference VARCHAR(120) NULL,
  is_invasive TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- visitor (from ERD "Visitors")
CREATE TABLE visitors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(80) NOT NULL,
  last_name VARCHAR(80) NOT NULL,
  age_group ENUM('child','teen','adult','senior') NOT NULL,
  gender ENUM('female','male','nonbinary','other','prefer_not_to_say') NULL,
  height_cm INT NULL,
  country_of_origin VARCHAR(80) NULL,
  is_citizen TINYINT(1) NULL,
  address VARCHAR(200) NULL,
  email VARCHAR(120) NULL,
  phone_number VARCHAR(40) NULL
) ENGINE=InnoDB;

-- =========================
-- Link / association tables
-- =========================

-- location_animal (from ERD "LocationAnimalSpecies")
CREATE TABLE location_animal (
  id INT AUTO_INCREMENT PRIMARY KEY,
  location_id INT NOT NULL,
  animal_id INT NOT NULL,
  UNIQUE KEY uq_loc_animal (location_id, animal_id),
  CONSTRAINT fk_loc_an_loc FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE CASCADE,
  CONSTRAINT fk_loc_an_animal FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- location_activity (from ERD "LocationActivities")
CREATE TABLE location_activity (
  id INT AUTO_INCREMENT PRIMARY KEY,
  location_id INT NOT NULL,
  activity_id INT NOT NULL,
  UNIQUE KEY uq_loc_act (location_id, activity_id),
  CONSTRAINT fk_loc_act_loc FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE CASCADE,
  CONSTRAINT fk_loc_act_act FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- location_water (from ERD "LocationBodyOfWater")
CREATE TABLE location_water (
  id INT AUTO_INCREMENT PRIMARY KEY,
  location_id INT NOT NULL,
  water_id INT NOT NULL,
  UNIQUE KEY uq_loc_water (location_id, water_id),
  CONSTRAINT fk_loc_water_loc FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE CASCADE,
  CONSTRAINT fk_loc_water_water FOREIGN KEY (water_id) REFERENCES waters(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- location_vegetation (from ERD "LocationVegetation")
CREATE TABLE location_vegetation (
  id INT AUTO_INCREMENT PRIMARY KEY,
  location_id INT NOT NULL,
  vegetation_id INT NOT NULL,
  UNIQUE KEY uq_loc_veg (location_id, vegetation_id),
  CONSTRAINT fk_loc_veg_loc FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE CASCADE,
  CONSTRAINT fk_loc_veg_veg FOREIGN KEY (vegetation_id) REFERENCES vegetations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================
-- Event / policy tables
-- =========================

-- history (from ERD "History")
CREATE TABLE history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  location_id INT NOT NULL,
  founder VARCHAR(120) NULL,
  founded_date DATE NULL,
  most_interesting_fact TEXT NULL,
  major_historic_accident TEXT NULL,
  age_of_park INT NULL,
  was_native_land TINYINT(1) NULL,
  preservation_laws_enacted TEXT NULL,
  significant_restoration_year INT NULL,
  CONSTRAINT fk_hist_loc FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- policy (from ERD "Policies")
CREATE TABLE policies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  location_id INT NOT NULL,
  policy_name VARCHAR(140) NOT NULL,
  license_details TEXT NULL,
  policy_description TEXT NULL,
  category ENUM('safety','conservation','access','fees') NOT NULL,
  effective_date DATE NULL,
  expiry_date DATE NULL,
  penalty_details TEXT NULL,
  enforcement_agency VARCHAR(140) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  CONSTRAINT fk_policy_loc FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- natural_disaster (from ERD "NaturalDisasters")
CREATE TABLE disasters (
  id INT AUTO_INCREMENT PRIMARY KEY,
  location_id INT NOT NULL,
  disaster_type VARCHAR(120) NOT NULL,
  impact_details TEXT NULL,
  event_date DATE NULL,
  severity ENUM('minor','moderate','severe') NOT NULL,
  affected_area_sq_km DECIMAL(10,2) NULL,
  fatalities INT NULL,
  injuries_count INT NULL,
  evacuations INT NULL,
  recovery_duration_days INT NULL,
  estimated_damage_musd DECIMAL(12,2) NULL,
  CONSTRAINT fk_disaster_loc FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- visit (from ERD "Visits")
CREATE TABLE visits (
  id INT AUTO_INCREMENT PRIMARY KEY,
  visitor_id INT NOT NULL,
  location_id INT NOT NULL,
  activity_id INT NULL,
  days_spent INT NULL,
  visit_date DATE NOT NULL,
  visit_type VARCHAR(60) NULL,
  total_cost DECIMAL(10,2) NULL,
  payment_method ENUM('cash','card','online') NULL,
  feedback_rating TINYINT NULL,
  CONSTRAINT fk_visit_vis FOREIGN KEY (visitor_id) REFERENCES visitors(id) ON DELETE CASCADE,
  CONSTRAINT fk_visit_loc FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE CASCADE,
  CONSTRAINT fk_visit_act FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- injury (from ERD "Injuries")
CREATE TABLE injuries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  visitor_id INT NOT NULL,
  location_id INT NOT NULL,
  injury_name VARCHAR(140) NOT NULL,
  injury_type VARCHAR(120) NULL,
  recovery_time_days INT NULL,
  body_part_affected VARCHAR(120) NULL,
  severity_level ENUM('minor','moderate','severe') NULL,
  treatment_provided VARCHAR(180) NULL,
  treatment_on_site TINYINT(1) NULL,
  hospital_referred TINYINT(1) NULL,
  incident_description TEXT NULL,
  reported_by VARCHAR(120) NULL,
  CONSTRAINT fk_injury_vis FOREIGN KEY (visitor_id) REFERENCES visitors(id) ON DELETE CASCADE,
  CONSTRAINT fk_injury_loc FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;