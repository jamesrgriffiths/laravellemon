<!-- Allows updating of a given field's data, takes a single field and shows edits in a textarea
   modal_id - The id for reference.
   index - The index of the object in the array the object is a part of.
   label - The name to use for labelling this modal.
   field - The field that is being updated.
   value - The current value of the field.
   - emit 'save' - sends back updated object and index
 -->
<template>
  <div class="modal fade" :id="modal_id" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div>
            <div class="modal-title text-info h5">{{label}}</div>
          </div>
        </div>
        <div class="modal-body">
          <textarea v-model="working_value" class="form-control" rows="8" cols="80"></textarea>
        </div>
        <div class="modal-footer">
          <button v-on:click="reset()" type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Cancel</button>
          <button v-on:click="$emit('save',field,working_value,index); reset();" type="button" class="btn btn-sm btn-outline-success" data-dismiss="modal">Save</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() { return { working_value: '' } },
    created() {
      this.reset();
      let vm = this;
      $(document).on('show.bs.modal','#'+vm.modal_id, function() { vm.reset(); });
    },
    props: ['modal_id', 'index', 'label', 'field', 'value'],
    methods: {
      reset() {
        this.working_value = this.value;
      }
    }
  };
</script>
