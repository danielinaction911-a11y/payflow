<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
   protected $fillable = ['user_id', 'title', 'body', 'type', 'image', 'is_read'];

   protected function casts(): array
   {
      return ['is_read' => 'boolean'];
   }

   const info = 'info';
   const success = 'success';
   const warning = 'warning';
   const error = 'error';

   /* type */
   public function getTypeIconAttribute()
   {
      return match ($this->type) {
         self::info => 'mdi:information-outline',
         self::success => 'mdi:check-circle-outline',
         self::warning => 'mdi:alert-circle-outline',
         self::error => 'mdi:close-circle-outline',
         default => 'mdi:bell-outline',
      };
   }

   public function getTypeBadgeClassAttribute()
   {
      return match ($this->type) {
         self::info => 'bg-info',
         self::success => 'bg-success',
         self::warning => 'bg-warning',
         self::error => 'bg-danger',
         default => 'bg-secondary',
      };
   }


   public function typeIcon(): string
   {
      return match ($this->type) {
         self::info => 'fa-info-circle',
         self::success => 'fa-check-circle',
         self::warning => 'fa-exclamation-triangle',
         self::error => 'fa-times-circle',
         default => 'fa-info-circle',
      };
   }

   public function typeColor(): string
   {
      return match ($this->type) {
         self::info => 'primary',
         self::success => 'success',
         self::warning => 'warning',
         self::error => 'danger',
         default => 'info',
      };
   }

   public function user()
   {
      return $this->belongsTo(User::class);
   }

   public function markAsRead()
   {
      $this->update(['is_read' => true]);
   }
}
