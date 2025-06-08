<?php

namespace App\Traits\utils;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

trait SlugTrait
{
    use HasSlug;

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom($this->getSlugSource())
            ->saveSlugsTo($this->getSlugDestination());
    }

    /**
     * Get the attribute from which to generate the slug.
     */
    protected function getSlugSource(): string
    {
        return property_exists($this, 'slugSource') ? $this->slugSource : 'name';
    }

    /**
     * Get the attribute where the slug should be saved.
     */
    protected function getSlugDestination(): string
    {
        return property_exists($this, 'slugDestination') ? $this->slugDestination : 'slug';
    }

    public function getRouteKeyName()
    {
        return $this->getSlugDestination();
    }
}
