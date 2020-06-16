<!-- Allows updating of the a given object's route_access variable
   modal_id - The id for reference.
   index - The index of the object in the array the object is a part of.
   label - The name to use for labelling this modal.
   object - The object.
   - emit 'save' - sends back updated object and index
 -->
<template>
  <div class="modal fade" :id="modal_id" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-info" id="modalLabel">{{label}}</h5>
        </div>
        <div class="modal-body">
          <div class="list-group">
            <button type="button"
              v-for="route in working_object.routes"
              v-if="route.active !== -1"
              class="list-group-item"
              v-bind:class="route.active == 1 ? 'list-group-item-primary' : 'list-group-item-light'"
              v-on:click="route.active = !route.active">
              {{route.name}}
            </button>
          </div>
        </div>
        <div class="modal-footer">
          <button v-on:click="reset()" type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Cancel</button>
          <button v-on:click="$emit('save',working_object,index); reset();" type="button" class="btn btn-sm btn-outline-success" data-dismiss="modal">Save</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() { return { working_object: '' } },
    created() {
      this.reset();
      let vm = this;
      $(document).on('show.bs.modal','#'+vm.modal_id, function() { vm.reset(); });
    },
    props: ['modal_id', 'index', 'label', 'object', 'fields', 'toggles'],
    methods: {
      reset() {
        this.working_object = JSON.parse(JSON.stringify(this.object));
      }
    }
  };
</script>
