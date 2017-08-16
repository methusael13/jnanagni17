
function Vector3(x, y, z) {
    this.x = x || 0;
    this.y = y || 0;
    this.z = z || 0;
};

Vector3.add = function(v0, v1) {
    if (v1 instanceof Vector3)
        return new Vector3(v0.x + v1.x, v0.y + v1.y, v0.z + v1.z);
    else
        return new Vector3(v0.x + v1, v0.y + v1, v0.z + v1);
};

Vector3.sub = function(v0, v1) {
    if (v1 instanceof Vector3)
        return new Vector3(v0.x - v1.x, v0.y - v1.y, v0.z - v1.z);
    else
        return new Vector3(v0.x - v1, v0.y - v1, v0.z - v1);
};

Vector3.dot = function(v0, v1) {
    return v0.x * v1.x + v0.y * v1.y + v0.z * v1.z;
};

Vector3.cross = function(v0, v1) {
    return new Vector3(
        v0.y * v1.z - v0.z * v1.y,
        v0.z * v1.x - v0.x * v1.z,
        v0.x * v1.y - v0.y * v1.x
    );
};

Vector3.prototype = {
    length: function() { return Math.sqrt(Vector3.dot(this, this)); },

    normalize: function() {
        var len = this.length();
        this.x /= len; this.y /= len; this.z /= len;
    },
};
