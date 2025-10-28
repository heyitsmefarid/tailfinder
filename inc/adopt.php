<?php
require_once 'db_connect.php';
$conn = connectDB();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Adopt a Pet</title>


  <style>
  /* 🌈 Adopt Section Styling */
  #adopt {
    background: linear-gradient(135deg, #eef6ff, #ffffff);
    padding: 80px 0;
  }

  #adopt .section-title {
    text-align: center;
    margin-bottom: 40px;
    animation: fadeInDown 0.8s ease-in-out;
  }

  #adopt .section-title h2 {
    font-size: 2.2rem;
    color: #1e3a8a;
    font-weight: 700;
    margin-bottom: 10px;
  }

  #adopt .section-title p {
    color: #475569;
    font-size: 1rem;
  }

  /* card */
  .pet-card {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
  }

  .pet-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
  }

  /* image */
  .pet-img {
    height: 220px;
    overflow: hidden;
    background: #f4f6fb;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .pet-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }

  .pet-card:hover .pet-img img {
    transform: scale(1.08);
  }

  /* info */
  .pet-info {
    padding: 18px;
    text-align: center;
  }

  .pet-info h3 {
    font-weight: 600;
    color: #1e3a8a;
    margin-bottom: 6px;
    font-size: 1.25rem;
  }

  .pet-info p {
    color: #475569;
    font-size: 0.95rem;
    margin: 4px 0;
  }

  .pet-info .desc {
    font-size: 0.9rem;
    color: #64748b;
    margin: 10px 0 15px;
    min-height: 45px;
  }

  /* button */
  .adopt-btn {
    background: linear-gradient(135deg, #1e40af, #3b82f6);
    color: #fff;
    padding: 10px 25px;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 3px 8px rgba(30, 64, 175, 0.3);
  }

  .adopt-btn:hover {
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(30, 64, 175, 0.4);
  }

  .no-pets {
    text-align: center;
    color: #64748b;
    font-size: 1.1rem;
    padding: 40px;
  }

  @keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-25px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @media (max-width: 992px) {
    .pet-img { height: 200px; }
  }

  @media (max-width: 576px) {
    #adopt { padding: 60px 15px; }
    .pet-img { height: 180px; }
  }
  </style>
</head>

<body>

<section id="adopt">
  <div class="container">
    <div class="section-title" data-aos="fade-up">
      <h2>Adopt a Pet</h2>
      <p>Find your perfect furry friend and give them a loving home today.</p>
    </div>

    <div class="row g-4 justify-content-center">
      <?php
      // ✅ Fetch 6 random available pets
      $stmt = $conn->prepare("SELECT * FROM pet_tbl WHERE pet_status = 'Available' ORDER BY RAND() LIMIT 6");
      $stmt->execute();
      $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if ($pets) {
        foreach ($pets as $pet) {
          $pet_image = htmlspecialchars($pet['image'] ?? '');
          $pet_name = htmlspecialchars($pet['pet_name'] ?? 'Unnamed');
          $breed = htmlspecialchars($pet['breed'] ?? 'Unknown');
          $age = htmlspecialchars($pet['age'] ?? 'Unknown');
          $desc = htmlspecialchars($pet['description'] ?? 'A lovely pet waiting for a home.');

          // ✅ Build correct image path
          $image_path = 'uploads/' . basename($pet_image);
          if (!file_exists($image_path) || empty($pet_image)) {
            $image_path = 'https://via.placeholder.com/400x300?text=No+Image';
          }
      ?>
          <div class="col-lg-4 col-md-6 col-sm-12" data-aos="zoom-in">
            <div class="pet-card h-100">
              <div class="pet-img">
                <img src="<?= $image_path ?>" alt="<?= $pet_name ?>">
              </div>
              <div class="pet-info">
                <h3><?= $pet_name ?></h3>
                <p><strong>Breed:</strong> <?= $breed ?></p>
                <p><strong>Age:</strong> <?= $age ?></p>
                <p class="desc"><?= $desc ?></p>
                <button class="adopt-btn" onclick="window.location.href='login/index.php'">Adopt Now</button>
              </div>
            </div>
          </div>
      <?php
        }
      } else {
        echo "<p class='no-pets'>No pets available for adoption right now. Please check back soon!</p>";
      }
      ?>
    </div>
  </div>
</section>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({ duration: 900, once: true });
</script>

</body>
</html>
