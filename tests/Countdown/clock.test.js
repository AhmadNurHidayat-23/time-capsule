import test from 'node:test';
import assert from 'node:assert/strict';
import {ServerClock} from '../../resources/js/clock.js';
test('countdown uses server time and monotonic elapsed time', () => {
    let tick = 500;
    const clock = new ServerClock(100000, 190000, () => tick);
    assert.deepEqual(clock.parts(), {days:0,hours:0,minutes:1,seconds:30});
    tick += 1500;
    assert.equal(clock.remaining(), 88500);
    assert.equal(clock.parts().seconds, 29);
});
test('large device wall-clock differences do not affect countdown', () => {
    let tick = 0;
    const clock = new ServerClock(1793754000000,1793847661000,() => tick);
    assert.deepEqual(clock.parts(), {days:1,hours:2,minutes:1,seconds:1});
    tick = 1000;
    assert.equal(clock.parts().seconds,0);
});
test('server resynchronization compensates for request round trip', () => {
    let tick = 0;
    const clock = new ServerClock(1000,5000,() => tick);
    tick = 200; clock.sync(2000,200);
    assert.equal(clock.remaining(),2900);
});
test('deadline is clamped at zero and can be corrected by server', () => {
    let tick = 0;
    const clock = new ServerClock(1000,2000,() => tick);
    tick = 5000;
    assert.deepEqual(clock.parts(), {days:0,hours:0,minutes:0,seconds:0});
    clock.sync(1500);
    assert.equal(clock.remaining(),500);
    assert.equal(clock.parts().seconds,1);
});
