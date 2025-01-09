@if (in_array($status, ['active', 1]))
    <i class="ki-duotone ki-check-square text-success fs-1" style="display: inline">
        <span class="path1"></span><span class="path2"></span></i>
@elseif (in_array($status, ['in-active', 0]))
    <i class="ki-duotone ki-cross-square text-danger fs-1" style="display: inline">
        <span class="path1"></span><span class="path2"></span></i>
@else
    --
@endif
