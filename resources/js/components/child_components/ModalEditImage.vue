<!-- Allows updating of an image
   modal_id - The id for reference.
   index - The index of the object in the array the object is a part of.
   label - The name to use for labelling this modal.
   field - The field name that is being updated.
   value - The current value of the field - the location of the image
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
        <div class="modal-body text-center">
          <input :id="'image_'+index" type="file" accept="image/jpeg,image/png" class="form-control-file" @change="updateFile">
          <br/><hr/>
          <img :src="source" class="lemon-image-thumbnail"/>
        </div>
        <div class="modal-footer">
          <button v-on:click="reset()" type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Cancel</button>
          <button v-on:click="$emit('save',field,new_image,index); reset();" type="button" class="btn btn-sm btn-outline-success" data-dismiss="modal">Save</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() { return { new_image: '', source: '' } },
    created() {
      this.reset();
      let vm = this;
      $(document).on('show.bs.modal','#'+vm.modal_id, function() { vm.reset(); });
    },
    props: ['modal_id', 'index', 'label', 'field', 'value'],
    methods: {
      reset() {
        this.new_image = '';
        this.source = this.value;
        $('#image_'+this.index).val('');
      },
      updateFile(event) {
        this.new_image = event.target.files[0];
        this.source = URL.createObjectURL(this.new_image);
      }
    }
  };
</script>
