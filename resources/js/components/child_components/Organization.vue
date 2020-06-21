<!-- Shows an organization with options for editing or deleting them in a row format
  index - The index of the organization - used for row coloring.
  organization - The organization data object.
  loading - The loading attribute.
  edit_modal_id - The id for referencing the edit modal.
  delete_modal_id - The id for referencing the delete modal.
  edit_disabled - DEFAULT FALSE
  delete_disabled - DEFAULT FALSE
-->
<template>
  <div class="row p-2" v-bind:class="index%2 ? 'bg-light' : ''">
    <div class="col col-12 col-md-8">
      <span class="font-weight-bold text-info h5">{{organization.name}}</span>
      <span class="text-secondary h6">&nbsp;({{organization.user_count}} {{organization.user_count == 1 ? 'User' : 'Users'}})</span><br/>
      <span class="font-weight-bold text-secondary h6">Sub Domain: {{organization.slug}}</span>
    </div>
    <div class="col col-12 col-md-4 my-auto text-right">
      <button type="button" class="btn btn-sm m-1" :class="edit_disabled ? 'btn-outline-secondary' : 'btn-outline-info'" data-toggle="modal" :data-target="'#'+edit_modal_id" :disabled="loading || edit_disabled">Edit</button>
      <button type="button" class="btn btn-sm m-1" :class="delete_disabled ? 'btn-outline-secondary' : 'btn-outline-danger'" data-toggle="modal" :data-target="'#'+delete_modal_id" :disabled="loading || delete_disabled">Delete</button>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['index', 'organization', 'loading', 'edit_modal_id', 'delete_modal_id', 'edit_disabled', 'delete_disabled']
  }
</script>
