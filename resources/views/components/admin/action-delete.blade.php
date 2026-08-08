@props(['action' => null, 'label' => 'Delete', 'confirm' => 'Are you sure?'])

<x-admin.confirm-dialog :action="$action" :label="$label" :message="$confirm" {{ $attributes }} />