<?php namespace DMA\Friends\API\Resources;

use DMA\Friends\Classes\API\BaseResource;
use DMA\Friends\API\Resources\UserTransformerInjectionTrait;

class BadgeResource extends BaseResource {

    use UserTransformerInjectionTrait;
    
    protected $model        = '\DMA\Friends\Models\Badge';
    protected $transformer  = '\DMA\Friends\API\Transformers\BadgeTransformer';

    /**
     * The listed actions that don't required check if 
     * user can perform the action
     * @var array
     */
    protected $skipUserPermissionValidation = [
            'index', 'show'
    ];
    
     
    /**
     * @SWG\Get(
     *     path="badges",
     *     description="Returns all badges",
     *     summary="Return all badges",
     *     tags={ "badges"},
     *     
     *     @SWG\Parameter(
     *         ref="#/parameters/authorization"
     *     ),
     *     @SWG\Parameter(
     *         ref="#/parameters/per_page"
     *     ),
     *     @SWG\Parameter(
     *         ref="#/parameters/page"
     *     ),
     *     @SWG\Parameter(
     *         ref="#/parameters/sort"
     *     ),
     *     
     *     @SWG\Parameter(
     *         description="Include a completed steps for a given user on each badge",
     *         format="int64",
     *         name="user",
     *         in="query",
     *         type="integer",
     *         required=false
     *     ), 
     *            
     *     @SWG\Response(
     *         response=200,
     *         description="Successful response",
     *         @SWG\Schema(ref="#/definitions/badge", type="array")
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @SWG\Schema(ref="#/definitions/error500")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Not Found",
     *         @SWG\Schema(ref="#/definitions/error404")
     *    )
     * )
     */
    public function index()
    {
        return parent::index();
    }
    
    /**
     * @SWG\Get(
     *     path="badges/{id}",
     *     description="Returns a badge by id",
     *     summary="Find a badge by id",
     *     tags={ "badges"},
     *
     *     @SWG\Parameter(
     *         ref="#/parameters/authorization"
     *     ),
     *     @SWG\Parameter(
     *         description="ID of badge to fetch",
     *         format="int64",
     *         in="path",
     *         name="id",
     *         required=true,
     *         type="integer"
     *     ),
     *
     *     @SWG\Parameter(
     *         description="Include a completed steps for a given user on each badge",
     *         format="int64",
     *         name="user",
     *         in="query",
     *         type="integer",
     *         required=false
     *     ), 
     *           
     *     @SWG\Response(
     *         response=200,
     *         description="Successful response",
     *         @SWG\Schema(ref="#/definitions/badge")
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Unexpected error",
     *         @SWG\Schema(ref="#/definitions/error500")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Not Found",
     *         @SWG\Schema(ref="#/definitions/error404")
     *     )
     * )
     */
    public function show($id)
    {
        return parent::show($id);
    }
    
}
