<!-- Modal for showing basic information.
  modal_id - The id for referencing this modal.
  title - Title of the popup.
  text - Text of the popup.
 -->
 <template>
   <div class="modal fade" :id="modal_id" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title text-info" id="modalLabel">{{title}}</h5>
         </div>
         <div class="modal-body">
           {{text}}
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-sm btn-outline-info" data-dismiss="modal">Done</button>
         </div>
       </div>
     </div>
   </div>
 </template>

 <script>
 export default {
   props: ['modal_id', 'title', 'text']
 }
 </script>
