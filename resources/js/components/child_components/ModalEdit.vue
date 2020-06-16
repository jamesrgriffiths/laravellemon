<!-- Allows updating of the a given object's data
   modal_id - The id for reference.
   index - The index of the object in the array the object is a part of.
   label - The name to use for labelling this modal.
   object - The object.
   fields - (OPTIONAL) An array of fields (name, display, type (select, DEFAULT input), options (required for select type, expects an object with id and name)) to update.
   toggles - (OPTIONAL) An array of fields (name, display, disabled - DEFAULT FALSE) to update via toggles or buttons.
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
          <div v-if="fields" v-for="field in fields" class="row mb-1">
            <div class="col col-3 my-auto h6">{{field.display}}</div>
            <div class="col col-9">
              <select v-if="field.type == 'select'" v-model="working_object[field.name]" class="form-control">
                <option value="0"></option>
                <option v-for="option in field.options" :value="option.id">{{option.name}}</option>
              </select>
              <input v-else type="text" class="form-control" v-model="working_object[field.name]">
            </div>
          </div>
          <div v-if="toggles">
            <div class="row mb-1">
              <div class="col col-12">
                <button v-for="toggle in toggles" v-on:click="working_object[toggle.name] = !working_object[toggle.name]" type="button"
                  class="btn btn-sm m-1" :class="working_object[toggle.name] ? 'btn-primary' : 'btn-outline-secondary'"
                  :disabled="toggle.disabled">{{toggle.display}}</button>
              </div>
            </div>
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
