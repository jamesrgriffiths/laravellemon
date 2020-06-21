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
    <div class="col col-9">
      <div class="row">
        <div class="col col-12 col-md-6">
          <span class="font-weight-bold" :class="user.id == current_user.id ? 'text-secondary' : 'text-info'">{{user.name}} ({{user.email}})</span>
          <br/>
          <span :class="user.organization ? 'text-info' : 'text-secondary'">{{ user.organization ? user.organization.name : 'Global' }}</span>
          &nbsp;-&nbsp;<span :class="!user.is_admin && !user.userType ? 'text-danger' : 'text-info'">
            {{user.is_admin ? 'Admin' : user.userType ? user.userType.name : 'No User Type'}}
          </span>
          <br/>
          <span :class="user.is_active ? 'text-success' : 'text-danger'">{{user.is_active ? 'Active' : 'Inactive'}}</span>
          <span :class="user.email_verified_at ? 'text-success' : 'text-danger'">{{user.email_verified_at ? 'Verified' : 'Unverified'}}</span>
        </div>
        <div class="col col-12 col-md-6 my-auto">
          Last Logged In:
          <span :class="user.last_logged_in_formatted ? 'text-info' : 'text-danger'">
            {{user.last_logged_in_formatted ? user.last_logged_in_formatted : 'Never Logged In'}}
          </span>
        </div>
      </div>
    </div>
    <div class="col col-3 my-auto text-right">
      <button type="button" class="btn btn-sm btn-outline-info m-1" data-toggle="modal" :data-target="'#'+edit_modal_id" :disabled="loading">Edit</button>
      <button type="button" class="btn btn-sm m-1" :class="user.id == current_user.id ? 'btn-outline-secondary' : 'btn-outline-danger'" data-toggle="modal" :data-target="'#'+delete_modal_id" :disabled="loading || user.id == current_user.id">Delete</button>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['index', 'user', 'loading', 'current_user', 'edit_modal_id', 'delete_modal_id']
  }
</script>
