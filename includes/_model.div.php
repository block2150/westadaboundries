

    <!-- Modal: Connect with Profile -->
    <div id="createRelationship" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="createRelationship" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>        
        <h4 id="createRelationshipLabel">Invite <span id="createRelationshipFullname"></span> to connect on Fotoluv.com</h4>
      </div>
      <div class="modal-body">
		<p>Connecting to people on Fotoluv.com helps build your network of industry professionals.</p>				
        
        <p class="bold">Include a personal note: (optional)</p>
        <textarea class="input-xxlarge" id="createRelationshipMessage"></textarea>
        <input type="hidden" id="createRelationshipID" name="createRelationshipID" value="" />
      </div>
      <div class="modal-footer">
        <button class="btn btn-inverse" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button id="createRelationshipButton" class="btn btn-danger" data-loading-text="Please wait..." onClick="createRelationshipConfirm();">Send Invite</button>
      </div>
    </div>



    <!-- Modal: Report Abuse -->
    <div id="reportAbuse" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="reportAbuse" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>        
        <h3 id="reportAbuseLabel">Report Abuse</h3>
      </div>
      <div class="modal-body">
        <p><span class="label label-important">Important</span> When you report an item for abuse, it will be removed from Fotoluv.com and reviewed for content.  If we find that the item is appropriate, it will be restored.  Also remember, excessive and false abuse reporting could band you from Fotoluv.com, so please make sure what your report has merit.</p>
        <br />
        
        <p class="bold">Comments:</p>
        
        <input type="hidden" id="reportAbuseUserID" name="reportAbuseUserID" value="" />
        <input type="hidden" id="reportAbuseTypeID" name="reportAbuseTypeID" value="" />
        <input type="hidden" id="reportAbuseType" name="reportAbuseType" value="" />
        <textarea class="input-xxlarge" id="reportAbuseComments" name="reportAbuseComments"></textarea>
        
     </div>
      <div class="modal-footer">
        <button class="btn btn-inverse" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button id="reportAbuseButton" class="btn btn-danger" data-loading-text="Please wait..." onClick="reportAbuseConfirm();">Report Abuse</button>
      </div>
    </div>




    <!-- Modal: Fotoluv Add To Board -->
    <div id="modelFotoluv" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modelFotoluv" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>        
        <h4 id="modelFotoluvLabel">Luv It!</h4>
      </div>
      <div class="modal-body">
      	<div class="pull-left"><img src="" id="modelFotoluvImg" width="200" class="thumbnail" /></div>
        <div class="pull-right">
        	<div id="modelFotoluvNewBoard" class="hide">
                <b>New Board</b><br />
                <input type="text" class="input-xlarge" id="modelFotoluvNewBoardName" style="height: 30px;" />
            </div>
            <div id="modelFotoluvSelectBoard">
                <b>Board</b><br />
                <select id="modelFotoluvBoardId" class="input-xlarge">
                </select>
        	</div>
			<div id="modelFotoluvNewBoardLink" class="link pull-right" onclick="modelFotoluvNewBoard();">
            	<i class="icon-plus-sign-alt"></i> add a new board
            </div>

            <br /><br />
            
        	<b>Comments</b><br />
        	<textarea class="input-xlarge" id="modelFotoluvComment"></textarea>
        </div>
        <input type="hidden" id="modelFotoluvLocation" name="modelFotoluvLocation" value="" />
        <input type="hidden" id="modelFotoluvID" name="modelFotoluvID" value="" />
        <input type="hidden" id="modelFotoluvSourceID" name="modelFotoluvSourceID" value="" />
        <input type="hidden" id="modelFotoluvFileName" name="modelFotoluvFileName" value="" />
      </div>
      <div class="modal-footer">
        <button class="btn btn-inverse" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button id="createRelationshipButton" class="btn btn-danger" data-loading-text="adding tob boards..." onClick="fotoluvAdd();">Add to Luv Boards</button>
      </div>
    </div>
