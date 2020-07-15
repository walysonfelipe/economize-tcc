<?php
    use Model\Department;
?>

<div class="searchSpaceMobile">
  <div class="searchBoxHeader" id="searchBoxHeader">
      <form class="formPesquisaHeader" method="get" action="<?= Project::baseUrlPhp(); ?>pesquisa">
            <input class="pesquisaTxtHeader" value="<?= isset($_GET['q']) ? $_GET['q'] : '' ; ?>" type="text" name="q" placeholder=" Clique e pesquise" title="Pesquise por produtos">
            <button class="pesquisaBtnHeader" type="submit">
                <i class="fas fa-search"></i>
            </button>
      </form>
  </div>
</div>

<?php
    $listDepartment = Department::listAll();

    if (count($listDepartment) > 0):?>
        <div class="menuCarousel owl-one owl-carousel departamentos">
            <?php
            foreach ($listDepartment as $row):?>
                <div class="celulaMenuCarousel">
                    <a class="linkBtnMenu" title="<?= ($row['depart_desc'] != "") ? $row['depart_desc'] : Project::formatFirstName($row['depart_nome']); ?>" href="<?= Project::baseUrlPhp() . $row['depart_url']; ?>">
                        <i class="<?= $row['depart_icon']; ?>"></i>
                        <h5 class="linkMenuCarousel"><?= $row['depart_nome']; ?></h5>
                    </a>
                </div>
                <?php
            endforeach;?>
        </div>
        <?php
    endif;
?>