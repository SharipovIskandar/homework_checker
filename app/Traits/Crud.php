<?php

namespace App\Traits;

trait Crud
{
    public function customStore($request)
    {
        $model = new $this->modelClass;
        // $model = $this->modelClass::create($request->only($model->getFillable()));
        $model = $this->modelClass::create($request->except(array_merge($model?->translatable ?? [], $model?->fileFields ?? [])));
        $model = $this->attachTranslates($model, $request);
        $this->attachFiles($model, $request);
        return $model;
    }

    public function attachTranslates($model, $request)
    {
        if (isset($model->translatable)) {
            if (is_array($model->translatable)) {
                $model->setTranslationsArray($request->only($model->translatable) ?? []);
            }
        }
        return $model;
    }

    public function attachFiles($model, $request): void
    {
        if ($model->fileFields) {
            foreach ($model->fileFields as $item) {
                if ($request->file($item))
                    $model->$item = uploadFile($request->file($item), $model->getTable(), $model->$item);
            }
            $model->save();
        }
    }

    public function customEdit($id)
    {
        return $this->modelClass::findOrFail($id);
    }

    public function customUpdate($id, $request)
    {

        $model = $this->modelClass::findOrFail($id);
        $model->update($request->except(array_merge($model?->translatable ?? [], $model?->fileFields ?? [])));
        // $model->update($request->validated());
        $model = $this->attachTranslates($model, $request);
        $this->attachFiles($model, $request);
        return $model;
    }

    public function customDelete($id)
    {
        $model = $this->modelClass::findOrFail($id);
        if ($model->fileFields) {
            foreach ($model->fileFields as $item) {
                deleteFile($model->$item);
            }
        }
        if (isset($model->translatable)) {
            $model->deleteTranslations();
        }
        return $model->delete();
    }
}
