<!-- Shows a user with options for editing or deleting them in a row format
  index - The index of the user - used for row coloring.
  user - The user data object.
  loading - The loading attribute.
  current_user - The current logged in user object.
  edit_modal_id - The id for referencing the edit modal.
  delete_modal_id - The id for referencing the delete modal.
-->
<template>
  <div class="row p-2" v-bind:class="index%2 ? 'bg-light' : ''">
    <div class="col col-12 col-md-6">
      <span class="font-weight-bold" :class="user.id == current_user.id ? 'text-secondary' : 'text-info'">{{user.name}} ({{user.email}})</span>
      <br/>
      <span v-if="user.is_admin" class="text-info">Admin</span>
      <span v-if="user.userType" class="text-info">{{user.userType.name}}</span>
      <span v-if="user.is_active" class="text-success">Active</span>
      <span v-else class="text-danger">Inactive</span>
      <br/>User Since: <span v-if="user.created_at_formatted" class="text-info">{{user.created_at_formatted}}</span><span v-else class="text-info">Manually Created</span>
      <br/>Last Login: <span v-if="user.last_logged_in_formatted" class="text-info">{{user.last_logged_in_formatted}}</span><span v-else class="text-danger">Never</span>
    </div>
    <div class="col col-12 col-md-6 my-auto text-right">
      <button type="button" class="btn btn-sm btn-outline-info" data-toggle="modal" :data-target="'#'+edit_modal_id" :disabled="loading">Edit</button>
      <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" :data-target="'#'+delete_modal_id" :disabled="loading || user.id == current_user.id">Delete</button>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['index', 'user', 'loading', 'current_user', 'edit_modal_id', 'delete_modal_id']
  }
</script>
