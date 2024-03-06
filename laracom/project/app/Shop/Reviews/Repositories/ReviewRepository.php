<?php

namespace App\Shop\Reviews\Repositories;

use App\Shop\AttributeValues\AttributeValue;
use App\Shop\Products\Exceptions\ProductCreateErrorException;
use App\Shop\Products\Exceptions\ProductUpdateErrorException;
use App\Shop\Tools\UploadableTrait;
use Jsdecena\Baserepo\BaseRepository;
use App\Shop\Brands\Brand;
use App\Shop\ProductAttributes\ProductAttribute;
use App\Shop\ProductImages\ProductImage;
use App\Shop\Products\Exceptions\ProductNotFoundException;
use App\Shop\Products\Product;
use App\Shop\Reviews\Repositories\Interfaces\ReviewRepositoryInterface;
use App\Shop\Products\Transformations\ProductTransformable;
use App\Shop\Reviews\Exceptions\ReviewCreateErrorException;
use App\Shop\Reviews\Review;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReviewRepository extends BaseRepository implements ReviewRepositoryInterface
{
    // use ProductTransformable, UploadableTrait;

    /**
     * ProductRepository constructor.
     * @param Product $product
     */
    public function __construct(Review $review)
    {
        parent::__construct($review);
        $this->model = $review;
    }

    /**
     * List all the products
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return Collection
     */
    public function listReviews(string $review = 'id', string $sort = 'desc', array $columns = ['*']) : Collection
    {
        return $this->all($columns, $review, $sort);
    }

    /**
     * Create the product
     *
     * @param array $data
     *
     * @return Product
     * @throws ProductCreateErrorException
     */
    public function createReview(array $data) : Review
    {
        try {
            return $this->create($data);
        } catch (QueryException $e) {
            throw new ReviewCreateErrorException($e);
        }
    }

    /**
     * Delete the product
     *
     * @param Product $product
     *
     * @return bool
     * @throws \Exception
     * @deprecated
     * @use removeProduct
     */
    public function deleteReview(Review $review) : bool
    {
        $review->images()->delete();
        return $review->delete();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function removeReview() : bool
    {
        return $this->model->where('id', $this->model->id)->delete();
    }


    /**
     * @param array $file
     * @param null $disk
     * @return bool
     */
    public function deleteFile(array $file, $disk = null) : bool
    {
        return $this->update(['cover' => null], $file['product']);
    }

    /**
     * @return bool
     */
    public function deleteCover(): bool
    {
        return $this->model->update(['cover' => null]);
    }


    /**
     * @param string $text
     * @return mixed
     */
    public function searchReview(string $text) : Collection
    {
        if (!empty($text)) {
            return $this->model->searchReview($text);
        } else {
            return $this->listReviews();
        }
    }

    /**
     * @return mixed
     */
    // public function findProductImages() : Collection
    // {
    //     return $this->model->images()->get();
    // }

    // /**
    //  * @param UploadedFile $file
    //  * @return string
    //  */
    // public function saveCoverImage(UploadedFile $file) : string
    // {
    //     return $file->store('products', ['disk' => 'public']);
    // }

    /**
     * Associate the product attribute to the product
     *
     * @param ProductAttribute $productAttribute
     * @return ProductAttribute
     */
    public function saveProductAttributes(ProductAttribute $productAttribute) : ProductAttribute
    {
        $this->model->attributes()->save($productAttribute);
        return $productAttribute;
    }


    /**
     * @param ProductAttribute $productAttribute
     * @param AttributeValue ...$attributeValues
     *
     * @return Collection
     */
    public function saveCombination(ProductAttribute $productAttribute, AttributeValue ...$attributeValues) : Collection
    {
        return collect($attributeValues)->each(function (AttributeValue $value) use ($productAttribute) {
            return $productAttribute->attributesValues()->save($value);
        });
    }

    /**
     * @return Collection
     */
    public function listCombinations() : Collection
    {
        return $this->model->attributes()->map(function (ProductAttribute $productAttribute) {
            return $productAttribute->attributesValues;
        });
    }

    /**
     * @param Brand $brand
     */
    public function saveBrand(Brand $brand)
    {
        $this->model->brand()->associate($brand);
    }
}
