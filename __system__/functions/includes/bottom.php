<?php
    use Model\Department;

    if (!isset($listDepartment)) {
        $listDepartment = Department::listAll();
    }

    if (count($listDepartment) > 0):?>
        <div class="menuCarouselMobile owl-mobile owl-carousel prodsMobile">
            <?php
            foreach ($listDepartment as $row):?>
                <div class="celulaMenuCarouselMobile">
                    <a class="linkBtnMenu" title="<?= ($row['depart_desc'] != "") ? $row['depart_desc'] : Project::formatFirstName($row['depart_nome']); ?>" href="<?= Project::baseUrlPhp() . $row['depart_url']; ?>">
                        <i class="<?= $row['depart_icon']; ?>"></i>
                        <h5 class="linkMenuCarouselMobile"><?= $row['depart_nome']; ?></h5>
                    </a>
                </div>
                <?php
            endforeach;?>
        </div>
        <?php
    else:?>
        <h3>Sem departamento(s) para pesquisa</h3>
        <?php
    endif;
?>

<div class="menuCarouselMobile owl-mobile owl-carousel prodsMobile">

</div>