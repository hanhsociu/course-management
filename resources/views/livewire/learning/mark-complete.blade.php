<div>
    <button wire:click="toggleComplete" type="button"
        class="btn {{ $isCompleted ? 'btn-outline-success' : 'btn-ulearn-cview' }} btn-sm">
        @if($isCompleted)
        <i class="fa fa-check"></i> Đã hoàn thành
        @else
        <i class="fa fa-check-circle"></i> Hoàn thành bài học
        @endif
    </button>
</div>
