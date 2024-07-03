<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes; // 소프트 딜리트 추가

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // 변경가능한 화이트 리스트(fillable)
    protected $fillable = [
        'account',
        'profile',
        'password',
        'name',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    // JSON으로 데이터를 변환할때 제외될 데이터(보통 민감 정보)
    protected $hidden = [
        'password',
        'refresh_token',
    ];

    /**
     * JSON으로 시리얼라이즈 할때, 날짜를 원하는 형식으로 포맷
     * 이 메소드가 없으면 디폴트는 UTC
     * 
     * @param \DataTimeInterface $date
     * 
     * @return String('Y-m-d H:i:s')
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Accessor : 특정 컬럼의 값을 원하는 형태로 가공
     */
    public function getGenderAttribute($value) {
        return $value == 0 ? '남자' : '여자';
    }
}
