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
    <div class="col col-12 col-md-8">
      <div class="row">
        <div class="col col-12 col-md-6">
          <span class="font-weight-bold" :class="user.id == current_user.id ? 'text-secondary' : 'text-info'">{{user.name}} ({{user.email}})</span>
        </div>
        <div class="col col-12 col-md-3">
          <span :class="user.is_active ? 'text-success' : 'text-danger'">{{user.is_active ? 'Active' : 'Inactive'}}</span>
          <span :class="user.email_verified_at ? 'text-success' : 'text-danger'">{{user.email_verified_at ? 'Verified' : 'Unverified'}}</span>
          <span :class="!user.is_admin && !user.userType ? 'text-danger' : 'text-info'">
            {{user.is_admin ? 'Admin' : user.userType ? user.userType.name : 'No User Type'}}
          </span>
        </div>
        <div class="col col-12 col-md-3" :class="user.last_logged_in_formatted ? 'text-info' : 'text-danger'">
          {{user.last_logged_in_formatted ? user.last_logged_in_formatted : 'Never Logged In'}}
        </div>
      </div>
    </div>
    <div class="col col-12 col-md-4 my-auto text-right">
      <button type="button" class="btn btn-sm btn-outline-info m-1" data-toggle="modal" :data-target="'#'+edit_modal_id" :disabled="loading">Edit</button>
      <button type="button" class="btn btn-sm btn-outline-danger m-1" data-toggle="modal" :data-target="'#'+delete_modal_id" :disabled="loading || user.id == current_user.id">Delete</button>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['index', 'user', 'loading', 'current_user', 'edit_modal_id', 'delete_modal_id']
  }
</script>
