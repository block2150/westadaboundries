
<?php if ($_SESSION['user_contributor'] == "1") { ?>

    <!-- Modal: Feature Portfolio -->
    <div id="featurePortfolio" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="featurePortfolio" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>        
        <h3 id="featurePortfolioLabel">Would you like to feature this portfolio?</h3>
      </div>
      <div class="modal-body">
        <p id="featurePortfolioText">Featuring this portfolio will put in the rotation to show up on the main home page and other sections of the site.  If you are sure you would like to feature this portfolio, click the "Confirm" button below.</p>
        
        
        <input type="hidden" id="feature_id" value="">
        
      </div>
      <div class="modal-footer">
        <button class="btn btn-inverse" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn btn-danger" onClick="featurePortfolioConfirm();">Confirm</button>
      </div>
    </div>

    
    
    <div class="side-well">
        
        <div class="side-well-title">
            <i class="icon-lock icon-white"></i>&nbsp;&nbsp;Admin Options
        </div>
        
        <div class="side-well-content">
            <div id="featurePortfolioLink" class="side-link link" onclick="featurePortfolio('<?php echo $_SESSION['profile_id'] ?>');"><i class="icon-heart black"></i> Feature this portfolio</div>
        </div>
        
    </div>

<?php } ?>
