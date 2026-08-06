    <div class="space-y-4">
        <h1 class="text-xl font-semibold">Pending Verifications</h1>

        <div class="space-y-3">
            @forelse ($documents as $document)
                <div class="bg-white p-4 rounded border">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium">
                                {{ $document->helperProfile->user->name ?? 'Helper #' . $document->helper_profile_id }}
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ $document->type }} — uploaded {{ $document->created_at->diffForHumans() }}
                            </p>
                            <a href="{{ Storage::disk('private')->url($document->file_path) }}"
                               target="_blank" class="text-blue-600 text-sm underline">
                                View document
                            </a>
                        </div>

                        <div class="flex gap-2">
                            <button wire:click="approve({{ $document->id }})"
                                    class="bg-green-600 text-white px-3 py-1.5 rounded text-sm">
                                Approve
                            </button>
                            <button wire:click="startReject({{ $document->id }})"
                                    class="bg-red-600 text-white px-3 py-1.5 rounded text-sm">
                                Reject
                            </button>
                        </div>
                    </div>

                    @if ($rejectingDocId === $document->id)
                        <div class="mt-3 border-t pt-3">
                            <textarea wire:model="rejectionReason" class="w-full border rounded p-2 text-sm"
                                      placeholder="Reason for rejection" rows="2"></textarea>
                            @error('rejectionReason') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            <div class="flex gap-2 mt-2">
                                <button wire:click="confirmReject" class="bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    Confirm Reject
                                </button>
                                <button wire:click="$set('rejectingDocId', null)" class="text-sm text-gray-600">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-gray-600">Nothing pending review.</p>
            @endforelse

            {{ $documents->links() }}
        </div>
    </div>
