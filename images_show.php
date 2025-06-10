<?php
include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');
header('Cache-Control: public, max-age=3600');

secure();
include('includes/header.php');

// Fetch core team members
$coreTeamQuery = "SELECT * FROM images WHERE member_type = 'core_team' ORDER BY uploaded_at";
$coreTeamResult = $connect->query($coreTeamQuery);

// Fetch volunteers
$volunteersQuery = "SELECT * FROM images WHERE member_type = 'volunteer' ORDER BY uploaded_at";
$volunteersResult = $connect->query($volunteersQuery);
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow border-0 rounded">
                <div class="card-body">
                    <h2 class="card-title mb-4 text-center">Our Team</h2>
                    
                    <!-- Core Team Section -->
                    <h3 class="mb-4">Core Team</h3>
                    <div class="row mb-5">
                        <?php if ($coreTeamResult->num_rows > 0) { 
                            while ($member = $coreTeamResult->fetch_assoc()) { ?>
                            <div class="col-md-4 col-lg-3 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-img-top" style="height: 250px; overflow: hidden;">
                                        <img src="<?= htmlspecialchars($member['image_url'] ?? '') ?>" 
                                             alt="<?= htmlspecialchars($member['member_name'] ?? '') ?>" 
                                             class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($member['member_name'] ?? '') ?></h5>
                                        <p class="text-muted mb-2"><?= htmlspecialchars($member['member_role'] ?? '') ?></p>
                                        <?php if (!empty($member['member_bio'])) { ?>
                                            <p class="card-text small"><?= htmlspecialchars($member['member_bio']) ?></p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php }
                        } else { ?>
                            <div class="col-12">
                                <div class="alert alert-info text-center">No core team members found</div>
                            </div>
                        <?php } ?>
                    </div>
                    
                    <!-- Volunteers Section -->
                    <h3 class="mb-4">Our Volunteers</h3>
                    <div class="row">
                        <?php if ($volunteersResult->num_rows > 0) { 
                            while ($volunteer = $volunteersResult->fetch_assoc()) { ?>
                            <div class="col-6 col-md-3 col-lg-2 mb-4 text-center">
                                <img src="<?= htmlspecialchars($volunteer['image_url'] ?? '') ?>" 
                                     alt="<?= htmlspecialchars($volunteer['member_name'] ?? '') ?>" 
                                     class="rounded-circle mb-2" 
                                     style="width: 120px; height: 120px; object-fit: cover;">
                                <h6 class="mb-1"><?= htmlspecialchars($volunteer['member_name'] ?? '') ?></h6>
                                <small class="text-muted"><?= htmlspecialchars($volunteer['member_role'] ?? '') ?></small>
                            </div>
                            <?php }
                        } else { ?>
                            <div class="col-12">
                                <div class="alert alert-info text-center">No volunteers found</div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
