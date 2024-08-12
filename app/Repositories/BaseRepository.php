<?php

namespace App\Repositories;

//use App\Events\ActionLog;
//use App\Exceptions\GeneralException;
//use App\Models\AdsLog;
//use App\Utils\Helpers\CommonUtils;
use App\Utils\Helpers\LogsUtils;
//use App\Utils\Helpers\RequestUtils;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Class BaseRepository.
 */
abstract class BaseRepository implements RepositoryContract
{
    /**
     * The repository model.
     *
     * @var Model
     */
    protected $model;
    /**
     * The query builder.
     *
     * @var Builder
     */
    protected $query;
    /**
     * Alias for the query limit.
     *
     * @var int
     */
    protected $take;
    /**
     * Array of related models to eager load.
     *
     * @var array
     */
    protected $with = [];
    /**
     * Array of one or more where clause parameters.
     *
     * @var array
     */
    protected $wheres = [];
    /**
     * Array of one or more where in clause parameters.
     *
     * @var array
     */
    protected $whereIns = [];
    /**
     * Array of one or more ORDER BY column/value pairs.
     *
     * @var array
     */
    protected $orderBys = [];
    /**
     * Array of scope methods to call on the model.
     *
     * @var array
     */
    protected $scopes = [];

    protected $objectName = 'name';

    protected $objectType = null;

    protected $authUser;

    const DELETE = 'delete';

    /**
     * BaseRepository constructor.
     */
    public function __construct($authUser = null)
    {
        $this->makeModel();

        // set auth user
        $this->authUser = $authUser;
        if ($this->authUser == null) {
            $this->authUser = auth()->user();
        }
    }

    /**
     * Specify Model class name.
     *
     * @return mixed
     */
    abstract public function model();

    /**
     * @return Model|mixed
     * @throws GeneralException
     */
    public function makeModel()
    {
        $model = app()->make($this->model());
        if (!$model instanceof Model) {
            throw new GeneralException("Class {$this->model()} must be an instance of " . Model::class);
        }
        return $this->model = $model;
    }

    /**
     * Get all the model records in the database.
     *
     * @param array $columns
     *
     * @return Collection|static[]
     */
    public function all(array $columns = ['*'])
    {
        $this->newQuery()->eagerLoad();
        $models = $this->query->get($columns);
        $this->unsetClauses();
        return $models;
    }

    /**
     * Count the number of specified model records in the database.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->get()->count();
    }

    /**
     * Create a new model record in the database.
     *
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data)
    {
        $this->unsetClauses();
        return $this->model->create($data);
    }

    /**
     * Create one or more new model records in the database.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createMultiple(array $data)
    {
        $models = new Collection();
        foreach ($data as $d) {
            $models->push($this->create($d));
        }
        return $models;
    }

    /**
     * Delete one or more model records from the database.
     *
     * @return mixed
     */
    public function delete()
    {
        $this->newQuery()->setClauses()->setScopes();
        $result = $this->query->delete();
        $this->unsetClauses();
        return $result;
    }

    /**
     * Delete the specified model record from the database.
     *
     * @param $id
     *
     * @return bool|null
     * @throws \Exception
     */
    public function deleteById($id): bool
    {
        $this->unsetClauses();
        $obj = $this->model->find($id);
        if ($obj != null) {
            try {
                if ($obj->delete()) {
                    LogsUtils::fireEventUserActionLog(self::DELETE, \Illuminate\Support\Str::singular($this->model->getTable()), $obj->id, $obj);
                }
                return true;
            } catch (QueryException $exception) {
                throw new \App\Exceptions\QueryException();
            } catch (\Exception $e) {
            }
        }
        return false;
    }

    /**
     * Delete multiple records.
     *
     * @param array $ids
     *
     * @return int
     */
    public function deleteMultipleById(array $ids): int
    {
        return $this->model->destroy($ids);
    }

    /**
     * Get the first specified model record from the database.
     *
     * @param array $columns
     *
     * @return Model|static
     */
    public function first(array $columns = ['*'])
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();
        $model = $this->query->firstOrFail($columns);
        $this->unsetClauses();
        return $model;
    }

    /**
     * Get all the specified model records in the database.
     *
     * @param array $columns
     *
     * @return Collection|static[]
     */
    public function get(array $columns = ['*'])
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();
        $models = $this->query->get($columns);
        $this->unsetClauses();
        return $models;
    }

    /**
     * Get the specified model record from the database.
     *
     * @param       $id
     * @param array $columns
     *
     * @return Collection|Model
     */
    public function getById($id, array $columns = ['*'])
    {
        $this->unsetClauses();
        $this->newQuery()->eagerLoad();
        return $this->query->findOrFail($id, $columns);
    }

    /**
     * Get the specified model record from the database.
     *
     * @param $key
     * @param $keyValues
     * @param array $columns
     *
     * @param null $with
     * @param bool $filterDeleted
     * @return Collection|Model
     */
    public function getByKeys($key, $keyValues,
                              array $columns = ['*'],
        $with = null,
        $filterDeleted = false,
        $withTrahed = false)
    {
        $query = $this->model->select($columns);

        if ($withTrahed) {
            $query->withTrashed();
        }
        return $with === null ?
            $query->whereIn($key, $keyValues)->get() :
            $query->with($with)->whereIn($key, $keyValues)->get();
    }

    /**
     * @param       $item
     * @param       $column
     * @param array $columns
     *
     * @return Model|null|static
     */
    public function getByColumn($item, $column, array $columns = ['*'])
    {
        $this->unsetClauses();
        $this->newQuery()->eagerLoad();
        return $this->query->where($column, $item)->first($columns);
    }

    /**
     * @param int $limit
     * @param array $columns
     * @param string $pageName
     * @param null $page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($limit = 25, array $columns = ['*'], $pageName = 'page', $page = null)
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();
        $models = $this->query->paginate($limit, $columns, $pageName, $page);
        $this->unsetClauses();
        return $models;
    }

    /**
     * Update the specified model record in the database.
     *
     * @param       $id
     * @param array $data
     * @param array $options
     *
     * @return Collection|Model
     */
    public function updateById($id, array $data, array $options = [])
    {
        $this->unsetClauses();
        $model = $this->getById($id);
        $model->update($data, $options);
        return $model;
    }

    /**
     * Set the query limit.
     *
     * @param int $limit
     *
     * @return $this
     */
    public function limit($limit)
    {
        $this->take = $limit;
        return $this;
    }

    /**
     * Set an ORDER BY clause.
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'asc')
    {
        $this->orderBys[] = compact('column', 'direction');
        return $this;
    }

    /**
     * Add a simple where clause to the query.
     *
     * @param string $column
     * @param string $value
     * @param string $operator
     *
     * @return $this
     */
    public function where($column, $value, $operator = '=')
    {
        $this->wheres[] = compact('column', 'value', 'operator');
        return $this;
    }

    /**
     * Add a simple where in clause to the query.
     *
     * @param string $column
     * @param mixed $values
     *
     * @return $this
     */
    public function whereIn($column, $values)
    {
        $values = is_array($values) ? $values : [$values];
        $this->whereIns[] = compact('column', 'values');
        return $this;
    }

    /**
     * Set Eloquent relationships to eager load.
     *
     * @param $relations
     *
     * @return $this
     */
    public function with($relations)
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }
        $this->with = $relations;
        return $this;
    }

    protected function selectMax($fieldName, $newFieldName = null)
    {
        return DB::raw("max($fieldName) as " . ($newFieldName === null ? $fieldName : $newFieldName));
    }

    protected function selectMin($fieldName, $newFieldName = null)
    {
        return DB::raw("min($fieldName) as " . ($newFieldName === null ? $fieldName : $newFieldName));
    }

    protected function selectSum($fieldName, $newFieldName = null)
    {
        return DB::raw("sum($fieldName) as " . ($newFieldName === null ? $fieldName : $newFieldName));
    }

    protected function selectTwoSum($fistFieldName, $secondFieldName, $newFieldName)
    {
        return DB::raw("sum($fistFieldName) + sum($secondFieldName) as " . $newFieldName);
    }

    protected function ifCondition($condition, $trueValue = 1, $falseValue = 0)
    {
        return DB::raw("if($condition, $trueValue, $falseValue)");
    }

    protected function selectSumCondition($condition, $newFieldName = null)
    {
        if ($newFieldName == null) {
            $newFieldName = $condition;
        }
        $sumQuery = DB::raw("sum($condition)");
        return DB::raw("COALESCE($sumQuery, 0) as " . $newFieldName);
    }

    /**
     * Create a new instance of the model's query builder.
     *
     * @return $this
     */
    protected function newQuery()
    {
        $this->query = $this->model->newQuery();
        return $this;
    }

    /**
     * Add relationships to the query builder to eager load.
     *
     * @return $this
     */
    protected function eagerLoad()
    {
        foreach ($this->with as $relation) {
            $this->query->with($relation);
        }
        return $this;
    }

    /**
     * Set clauses on the query builder.
     *
     * @return $this
     */
    protected function setClauses()
    {
        foreach ($this->wheres as $where) {
            $this->query->where($where['column'], $where['operator'], $where['value']);
        }
        foreach ($this->whereIns as $whereIn) {
            $this->query->whereIn($whereIn['column'], $whereIn['values']);
        }
        foreach ($this->orderBys as $orders) {
            $this->query->orderBy($orders['column'], $orders['direction']);
        }
        if (isset($this->take) and !is_null($this->take)) {
            $this->query->take($this->take);
        }
        return $this;
    }

    /**
     * Set query scopes.
     *
     * @return $this
     */
    protected function setScopes()
    {
        foreach ($this->scopes as $method => $args) {
            $this->query->$method(...$args);
        }
        return $this;
    }

    /**
     * Reset the query clause parameter arrays.
     *
     * @return $this
     */
    protected function unsetClauses()
    {
        $this->wheres = [];
        $this->whereIns = [];
        $this->scopes = [];
        $this->take = null;
        return $this;
    }

    /**
     * Add the given query scope.
     *
     * @param string $scope
     * @param array $args
     *
     * @return $this
     */
    public function __call($scope, $args)
    {
        $this->scopes[$scope] = $args;
        return $this;
    }

    protected function getData($query,
                               $limit = 10,
                               $offset = 0,
                               $orderBy = [],
                               $orderType = [])
    {
        if ($limit > 0) {
            $query->skip($offset)->take($limit);
        }
        if (defined("\\" . get_class($this->model) . '::ORDERABLE_COLUMNS')) {
            for ($i = 0; $i < count($orderBy); $i++) {
                if (in_array($orderBy[$i], $this->model::ORDERABLE_COLUMNS)) {
                    $query->orderBy($orderBy[$i], $orderType[$i]);
                } else {
                    throw ValidationException::withMessages(['sortBy' => ['Không thể sắp xếp theo cột này']]);
                }
            }
        } else {
            for ($i = 0; $i < count($orderBy); $i++) {
                $query->orderBy($orderBy[$i], $orderType[$i]);
            }
        }
//        DB::enableQueryLog();
//        $query->get();
//        dd(DB::getQueryLog());
        return $query->get();
    }

    protected function updateObjectById($id, $arr, $objectType)
    {
        $object = $this->model->find($id);
        if ($object != null) {
            $object->fill($arr);
            $isSuccess = $this->saveModelAndFireLog($object, 'EDIT', $objectType, $object->id, $object);
            return !$isSuccess ? false : $this->getById($object->id);
        }
        return false;
    }

    protected function deletedByUser($id, $objectType)
    {
        $object = $this->model->find($id);
        $userId = auth()->id();
        if ($object != null && $object->deleted_at == null) {
            $object->deleted_by = $userId;
            $object->deleted_at = Carbon::now();
            return $this->saveModelAndFireLog($object, self::DELETE, $objectType, $id, $object);
        }
        return false;
    }

    protected function hardDeletedByUser($id, $objectType)
    {
        $object = $this->model->find($id);
        if ($object && $object->delete()) {
            LogsUtils::fireEventUserActionLog(self::DELETE, $objectType, $id, $object);
            return true;
        }
        return false;
    }

    public function massDelete($ids, $objectType)
    {
        DB::beginTransaction();
        $type = $objectType ? $objectType : $this->objectType;
        $i = 0;
        $deleted = true;
        while ($i < count($ids) && $deleted) {
            $result = $this->deleteById($ids[$i]);
            $deleted = $result;
            $i++;
        }

        if ($deleted) {
            DB::commit();
            return true;
        }
        DB::rollBack();
        return false;
    }

    public function massApprove($ids, $objectType)
    {
        DB::beginTransaction();
        $type = $objectType ? $objectType : $this->objectType;
        $i = 0;
        $deleted = true;
        while ($i < count($ids) && $deleted) {
            $result = $this->approve($ids[$i]);
            $deleted = $result;
            $i++;
        }
        if ($deleted) {
            DB::commit();
            return true;
        }
        DB::rollBack();
        return false;
    }

    public function massReject($ids, $objectType)
    {
        DB::beginTransaction();
        $type = $objectType ? $objectType : $this->objectType;
        $i = 0;
        $deleted = true;
        while ($i < count($ids) && $deleted) {
            $result = $this->reject($ids[$i]);
            $deleted = $result;
            $i++;
        }

        if ($deleted) {
            DB::commit();
            return true;
        }

        DB::rollBack();
        return false;
    }

    protected function saveModelAndFireLog($saveModel,
                                           $actionType, $objectType,
                                           $objectId, $data = null, $reason = null): bool
    {
        try {
            if ($saveModel->save()) {
                LogsUtils::fireEventUserActionLog($actionType, $objectType, $saveModel->id, $data, $reason);
                return true;
            } else {
                LogsUtils::fireEventUserActionLog($actionType, $objectType, $objectId, $data, $reason, "fail");
                return false;
            }
        } catch (\Exception $e) {
            LogsUtils::fireEventUserActionLog($actionType, $objectType, $objectId, $data, $e->getMessage(), "fail");
            return false;
        }
    }

    protected function deleteModelAndFireLog($saveModel,
                                             $actionType, $objectType,
                                             $objectId, $data = null, $reason = null): bool
    {
        if ($saveModel->delete()) {
            LogsUtils::fireEventUserActionLog($actionType, $objectType, $saveModel->id, $data, $reason);
            return true;
        } else {
            LogsUtils::fireEventUserActionLog($actionType, $objectType, null, $data, $reason, "fail");
            return false;
        }
    }

    protected function getDataForTotal($query,
                                       $limit = 10,
                                       $offset = 0,
                                       $orderBy = [],
                                       $orderType = [],
                                       $isReport = null)
    {
        if ($limit > 0) {
            $query->skip($offset)->take($limit);
        }
        if (defined("\\" . get_class($this->model) . '::ORDERABLE_COLUMNS')) {
            for ($i = 0; $i < count($orderBy); $i++) {
                if (in_array($orderBy[$i], $this->model::ORDERABLE_COLUMNS)) {
                    $query->orderBy($orderBy[$i], $orderType[$i]);
                }
            }
        } else {
            for ($i = 0; $i < count($orderBy); $i++) {
                $query->orderBy($orderBy[$i], $orderType[$i]);
            }
        }
        if ($isReport) {
            $query->orderBy('user_id', 'DESC');
        } else {
            $query->orderBy('ads_id', 'ASC');
        }
//        DB::enableQueryLog();
//        $query->get();
//        dd(DB::getQueryLog());
        return $query->get();
    }

    protected function customFilterQuery($filter, $query)
    {
        RequestUtils::filterFromParams($filter, $query);
        return $query;
    }

//    protected function filterOwnerOrAdmin($query, $userIdColumn = 'user_id')
//    {
//        if (!auth()->user()->hasRole(ROLE_IS_ADMIN)) {
//            $query->where($userIdColumn, auth()->id());
//        }
//        return $query;
//    }
}
