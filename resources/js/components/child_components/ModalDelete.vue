<!-- Verifies deletion of an item before deleting.
  modal_id - The id used to reference this modal.
  text - The text displayed.
  - emit 'delete'
-->
<template>
  <div class="modal fade" :id="modal_id" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">{{text}}</h5>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Cancel</button>
          <button v-on:click="$emit('delete')" type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">Delete</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['modal_id', 'text']
  };
</script>
